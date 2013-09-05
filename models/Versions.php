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
		'_id' => array('type' => 'id'),
		'model' => array('type' => 'string', 'default' => '', 'null' => false),
		'model_id' => array('type' => 'string', 'default' => '', 'null' => false),
		'slug' => array('type' => 'string', 'default' => '', 'null' => false),
		'type' => array('type' => 'string', 'null' => true),
		'content' => array('type' => 'string'),
		'notes' => array('type' => 'string', 'default' => '', 'null' => false),
		'status' => array('type' => 'string', 'default' => 'active', 'null' => false),
		'created' => array('type' => 'datetime', 'default' => '', 'null' => false),
		'approved' => array('type' => 'datetime'),
		'deleted' => array('type' => 'datetime'),
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
		'model_id' => array(
			array('notEmpty', 'message' => 'a model id is required.'),
		),
		'content' => array(
			array('notEmpty', 'message' => 'content is required.'),
		),
	);

	public static function available($model, $id = 'null', array $options = array()) {
		$conditions = compact('model');
		if (!empty($id)) {
			$key = $model::meta('key');
			$conditions[$key] = $id;
		}
		$options['conditions'] = $conditions;
		$versions = static::find('all', $options);
		return $versions;
	}

	public static function add($entity, array $options = array()) {
		$defaults = array();
		$options += $defaults;
		$params = compact('entity', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$model = $entity->model();
			$key = $model::meta('key');
			$export = $entity->export();
			$updated = Set::diff($self::cleanData($export['update']), $self::cleanData($export['data']));
			$data = array(
				'model' => $model,
				'model_id' => (string) $entity->$key,
				'updated' => json_encode($updated),
				'data' => json_encode($entity->data()),
				'created' => time(),
			);
			$version = $self::create($data);
			return $version->save();
		});
	}

	public static function restore($id, array $options = array()) {
		$defaults = array('validate' => false);
		$options += $defaults;
		$params = compact('id', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$version = static::first($id);
			if (!$version) {
				return false;
			}
			// TODO: restore data for $version->model with $version->id
			return true;
		});
	}

	/**
	 * returns content of entity data
	 *
	 * @see radium\data\Converter::get()
	 * @param object $version instance of current record
	 * @param array $data additional data to be passed into render context
	 * @param array $options additional options to be passed into `Converter::get()`
	 * @return array restored entity data
	 */
	public function content($version, $data = array(), array $options = array()) {
		return Converter::get('json', $version->content, $data, $options);
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