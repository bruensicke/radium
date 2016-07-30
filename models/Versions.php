<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use lithium\util\Set;
use radium\data\Converter;

class Versions extends \radium\models\BaseModel {

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_status = array(
		'active' => 'Active',
		'outdated' => 'Outdated',
		'review' => 'Review',
		'approved' => 'Approved',
	);

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array();

	/**
	 * Stores the data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'model' => array('type' => 'string', 'default' => '', 'null' => false),
		'foreign_id' => array('type' => 'string', 'default' => '', 'null' => false),
		'data' => array('type' => 'string'),
		'fields' => array('type' => 'object'),
		'approved' => array('type' => 'datetime'),
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validates = array(
		'_id' => array(
			array('notEmpty', 'message' => 'a unique _id is required.', 'last' => true, 'on' => 'update'),
		),
		'model' => array(
			array('notEmpty', 'message' => 'a model is required.'),
		),
		'foreign_id' => array(
			array('notEmpty', 'message' => 'a foreign id is required.'),
		),
		'data' => array(
			array('notEmpty', 'message' => 'content is required.'),
		),
	);

	/**
	 * Default query parameters.
	 *
	 * @var array
	 */
	protected $_query = array(
		'limit' => 200,
		'order' => array(
			'created' => 'DESC',
		),
	);

	/**
	 * Returns list of available Versions for a given model.
	 *
	 * @param string $model full-namespaced class-name to search for Versions
	 * @param string $id optional, to only show Versions for objects with this `$id`
	 * @param array $options additional find-options
	 * @return object A Collection object of all found Versions
	 */
	public static function available($model, $id = null, array $options = array()) {
		$conditions = compact('model');
		if (!empty($id)) {
			$key = $model::meta('key');
			$conditions[$key] = $id;
		}
		$options['conditions'] = $conditions;
		$versions = static::find('all', $options);
		return $versions;
	}


	/**
	 * This method generates a new version.
	 *
	 * It creates a duplication of the object, to allow restoring. It marks all prior
	 * versions as `outdated` and the new one as `active`.
	 *
	 * You probably want to create a new version of an entity, whenever save is called. To achieve
	 * this, you have to take care, all data is set into the entity and Versions::add with updated
	 * entity is called.
	 *
	 * In the following example you can see, how a meta-field, `versions` is used, to decide if
	 * a version needs to be created, or not.
	 *
	 * {{{
	 *	public function save($entity, $data = array(), array $options = array()) {
	 *		if (!empty($data)) {
	 *			$entity->set($data);
	 *		}
	 *		if (!isset($options['callbacks']) || $options['callbacks'] !== false) {
	 *			$versions = static::meta('versions');
	 *			if (($versions === true) || (is_callable($versions) && $versions($entity, $options))) {
	 *				$version_id = Versions::add($entity, $options);
	 *				if ($version_id) {
	 *					$entity->set(compact('version_id'));
	 *				}
	 *			}
	 *		}
	 *		return parent::save($entity, null, $options);
	 *	}
	 * }}}
	 *
	 * You have to set `versions` to true, in meta like this:
	 *
	 * {{{
	 *  $model::meta('versions', true);
	 * // OR
	 *  static::meta('versions', function($entity, $options){
	 *		return (bool) Environment::is('production');
	 *	});
	 * }}}
	 *
	 * @param object $entity the instance, that needs to created a new version for
	 * @param array $options additional options
	 * @filter
	 */
	public static function add($entity, array $options = array()) {
		$defaults = array('force' => false);
		$options += $defaults;
		$params = compact('entity', 'options');
		return static::_filter(get_called_class() . '::add', $params, function($self, $params) {
			extract($params);
			$model = $entity->model();
			if ($model == $self || !$entity->exists()) {
				return false;
			}
			$key = $model::meta('key');
			$foreign_id = (string) $entity->$key;

			$export = $entity->export();
			$updated = Set::diff($self::cleanData($export['update']), $self::cleanData($export['data']));

			if (empty($updated)) {
				if (!$options['force']) {
					return false;
				}
				$updated = $entity->data();
			}

			$self::update(array('status' => 'outdated'), compact('model', 'foreign_id'));

			$data = array(
				'model' => $model,
				'foreign_id' => $foreign_id,
				'status' => 'active',
				'name' => (string) $entity->title(),
				'fields' => $updated,
				'data' => json_encode($entity->data()),
				'created' => time(),
			);

			$version = $self::create($data);
			if (!$version->save()) {
				return false;
			}
			return $version->id();
		});
	}

	/**
	 * Restores a version from history and updates the corresponding record with stored data.
	 *
	 * All versions will be marked as `outdated` with the new version becoming `active`.
	 *
	 * @see radium\models\Versions::add()
	 * @param string $id Id of version to restore
	 * @param array $options additional options to be passed into $model::save()
	 * @return true on success, false otherwise
	 * @filter
	 */
	public static function restore($id, array $options = array()) {
		$defaults = array('validate' => false, 'callbacks' => false);
		$options += $defaults;
		$params = compact('id', 'options');
		return static::_filter(get_called_class() . '::restore', $params, function($self, $params) use ($defaults) {
			extract($params);
			$version = $self::first($id);
			if (!$version) {
				return false;
			}
			$model = $version->model;
			$foreign_id = $version->foreign_id;
			$data = json_decode($version->data, true);
			$data['version_id'] = $version->id();

			$entity = $model::first($foreign_id);
			if (!$entity) {
				$entity = $model::create($data);
			}

			if(!$entity->save($data, $options)) {
				return false;
			}

			$self::update(array('status' => 'outdated'), compact('model', 'foreign_id'));
			return $version->save(array('status' => 'active'), $defaults);
		});
	}

	/**
	 * only use data of objects, in case they are contained within data
	 *
	 * @param array $data passed in data
	 * @return array returns data, without continaing objects
	 */
	public static function cleanData(array $data = array()) {
		foreach($data as $key => $item) {
			if (is_array($item)) {
				$data[$key] = static::cleanData($item);
			}
			if ($item instanceof \lithium\data\Entity) {
				$data[$key] = $item->data();
			}
		}
		return $data;
	}
}

?>
