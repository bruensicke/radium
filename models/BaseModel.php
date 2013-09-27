<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\models\Configurations;
use radium\util\IniFormat;

use lithium\core\Libraries;
use lithium\util\Set;
use lithium\util\Validator;
use lithium\util\Inflector;

/**
 * Base class for all Models
 *
 * If you have models in your app, you should extend this class like that:
 *
 * {{{
 *  class MyModel extends \radium\models\BaseModel {
 * }}}
 *
 * @see app\models
 * @see lithium\data\Model
 */
class BaseModel extends \lithium\data\Model {

	/**
	 * Custom status options
	 *
	 * @var array
	 */
	public static $_status = array(
		'active' => 'active',
		'inactive' => 'inactive',
	);

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array();

	/**
	 * Custom find query properties, indexed by name.
	 *
	 * @var array
	 */
	public $_finders = array(
		'deleted' => array(
			'conditions' => array(
				'deleted' => array('>=' => 1),
			)
		)
	);

	/**
	 * Default query parameters.
	 *
	 * @var array
	 */
	protected $_query = array(
		'order' => array(
			'updated' => 'DESC',
			'created' => 'DESC',
		),
		'conditions' => array(
			'deleted' => null,
		),
	);

	/**
	 * initialize method
	 *
	 * @see lithium\data\Model
	 * @return void
	 */
	public static function __init() {
		Inflector::rules('uninflected', 'status');
		if (!Validator::rules('slug')) {
			Validator::add('slug', '/^[a-z0-9\_\-\.]*$/');
		}
		if (!Validator::rules('loose_slug')) {
			Validator::add('loose_slug', '/^[a-zA-Z0-9\_\-\.]*$/');
		}
		if (!Validator::rules('strict_slug')) {
			Validator::add('strict_slug', '/^[a-z][a-z0-9\_\-]*$/');
		}
		if (!Validator::rules('isUnique')) {
			Validator::add('isUnique', function ($value, $format, $options) {
				$conditions = array($options['field'] => $value);
				foreach ((array) $options['model']::meta('key') as $field) {
					if (!empty($options['values'][$field])) {
						$conditions[$field] = array('!=' => $options['values'][$field]);
					}
				}
				$fields = $options['field'];
				return is_null($options['model']::find('first', compact('fields', 'conditions')));
			});
		}
		if (!Validator::rules('status')) {
			Validator::add('status', function ($value, $format, $options) {
				return (bool) $options['model']::status($value);
			});
		}
		if (!Validator::rules('type')) {
			Validator::add('type', function ($value, $format, $options) {
				return (bool) $options['model']::type($value);
			});
		}

		if (!static::finder('random')) {
			static::finder('random', function($self, $params, $chain){
				$amount = $self::find('count', $params['options']);
				$offset = rand(0, $amount-1);
				$params['options']['offset'] = $offset;
				return $self::find('first', $params['options']);
			});
		}
		static::meta('versions', true);
	}

	/**
	 * overwritten to allow for soft-deleting a record
	 *
	 * The schema of the relevant model needs a field defined in schema called `deleted`.
	 * As soon as this is the case, the record does not get deleted right away but instead
	 * marked for deletion, i.e. setting a timestamp into the `deleted` field. Unless you make
	 * use of the `force` option, then the record will get deleted without further ado.
	 *
	 * @param object $entity current instance
	 * @param array $options Possible options are:
	 *     - `force`: set to true to delete record, anyway
	 * @return boolean true on success, false otherwise
	 */
	public function delete($entity, array $options = array()) {
		$options += array('force' => false);
		$deleted = $entity->schema('deleted');
		// TODO: use $deleted = $entity->hasField('deleted');
		if (is_null($deleted) || $options['force']) {
			unset($options['force']);
			return parent::delete($entity, $options);
		}
		$entity->deleted = time();
		return $entity->save();
	}

	/**
	 * automatically adds timestamps on saving.
	 *
	 * In case of creation it correctly fills the `created` field with a unix timestamp.
	 * Same holds true for `updated` on updates, accordingly.
	 *
	 * @see lithium\data\Model
	 * @param object $entity current instance
	 * @param array $data Any data that should be assigned to the record before it is saved.
	 * @param array $options additional options
	 * @return boolean true on success, false otherwise
	 * @filter
	 */
	public function save($entity, $data = array(), array $options = array()) {
		if (!empty($data)) {
			$entity->set($data);
		}
		if (!isset($options['callbacks']) || $options['callbacks'] !== false) {
			$field = ($entity->exists()) ? 'updated' : 'created';
			$entity->set(array($field => time()));
			$versions = static::meta('versions');
			if (($versions === true) || (is_callable($versions) && $versions($entity, $options))) {
				$version_id = Versions::add($entity, $options);
				if ($version_id) {
					$entity->set(compact('version_id'));
				}
			}
		}
		return parent::save($entity, null, $options);
	}


	/**
	 * returns primary id as string from current entity
	 *
	 * @param object $entity instance of current Record
	 * @return string primary id of current record
	 */
	public function id($entity) {
		return (string) $entity->{static::key()};
	}

	/**
	 * generic method to retrieve a list or an entry of an array of a static property
	 *
	 * This method is used to allow an easy addition of key/value pairs, mainly for usage
	 * in a dropdown for a specific model.
	 *
	 * If you want to provide a list of available options, declare your properties in the same
	 * manner as `$_types` or `$_status`.
	 *
	 * @see radium\models\BaseModel::types()
	 * @see radium\models\BaseModel::status()
	 * @param string $property name of property to look for.
	 *               automatically prepended by an underscore: `_`. Must be static and public
	 * @param string $type type to look for, optional
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function _group($property, $type = null) {
		$field = sprintf('_%s', $property);
		if (!empty($type)) {
			return (isset(static::$$field[$type])) ? static::$$field[$type] : false;
		}
		return static::$$field;
	}

	/**
	 * all types for current model
	 *
	 * @param string $type type to look for
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function types($type = null) {
		return static::_group(__FUNCTION__, $type);
	}

	/**
	 * all status for current model
	 *
	 * @param string $status status to look for
	 * @return mixed all status with keys and their name, or value of `$status` if given
	 */
	public static function status($status = null) {
		return static::_group(__FUNCTION__, $status);
	}

	/**
	 * finds and loads entities that match slug subpattern
	 *
	 * @see lithium\data\Model::find()
	 * @param string $slug short unique string to look for
	 * @param string $status status must have
	 * @param array $options additional options to be merged into find options
	 * @return object|boolean found results as collection or false, if none found
	 * @filter
	 */
	public static function search($slug, $status = 'active', array $options = array()) {
		$params = compact('slug', 'status', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$options['conditions'] = array(
				'slug' => array('like' => "/$slug/i"),
				'status' => $status,
				'deleted' => array('<=' => null), // only not deleted
			);
			$result = $self::find('all', $options);
			if (!$result) {
				return false;
			}
			return $result;
		});
	}

	/**
	 * finds and loads active entity for given id
	 *
	 * @param string $id id of entity to load
	 * @param string|array $status expected status of record, can be string or an array of strings
	 * @return object|boolean entity if found and active, false otherwise
	 * @filter
	 */
	public static function load($id, $status = 'active', array $options = array()) {
		$params = compact('id', 'status', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$defaults = array();
			$options += $defaults;
			$key = (strlen($id) == 24)
				? $self::key()
				: 'slug';
			$options['conditions'] = array($key => $id);
			$result = $self::find('first', $options);
			if (!$result) {
				return false;
			}
			if (!in_array($result->status, (array) $status)) {
				return false;
			}
			if (!empty($result->deleted)) {
				return false;
			}
			return $result;
		});
	}

	/**
	 * Returns all schema-fields, without their types
	 *
	 * @return array
	 */
	public static function fields() {
		$schema = static::schema();
		return $schema->names();
	}

	/**
	 * updates fields for multiple records, specified by key => value
	 *
	 * You can update the same field for more than on record with one call, like this:
	 *
	 * {{{
	 *   $data = array(
	 *     'id1' => 1,
	 *     'id2' => 2,
	 *   );
	 *   Model::multiUpdate('order', $data);
	 * }}}
	 *
	 * @param string $field name of field to update
	 * @param array $data array keys are primary keys, values will be set
	 * @param array $options Possible options are:
	 *     - `updated`: set to false to supress automatic updating of the `updated` field
	 * @return array an array containing all results
	 * @filter
	 */
	public static function multiUpdate($field, array $data, array $options = array()) {
		$defaults = array('updated' => true);
		$options += $defaults;
		$params = compact('field', 'data', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$key = static::key();
			$result = array();
			foreach ($data as $id => $value) {
				$update = array($field => $value);
				if ($options['updated']) {
					$update['updated'] = time();
				}
				$result[$id] = static::update($update, array($key => $id));
			}
			return $result;
		});
	}

	/**
	 * updates one or more fields per entity
	 *
	 * {{{$entity->updateFields(array('fieldname' => $value));}}}
	 *
	 * @see lithium\data\Model::update()
	 * @param object $entity current instance
	 * @param array $values an array of values to be changed
	 * @param array $options Possible options are:
	 *     - `updated`: set to false to supress automatic updating of the `updated` field
	 * @return true on success, false otherwise
	 * @filter
	 */
	public function updateFields($entity, array $values, array $options = array()) {
		$defaults = array('updated' => true);
		$options += $defaults;
		$params = compact('entity', 'values', 'options');
		return $this->_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$key = $self::key();
			$conditions = array($key => $entity->id());
			if ($options['updated']) {
				$values['updated'] = time();
			}
			$success = $self::update($values, $conditions);
			if (!$success) {
				$model = $entity->model();
				$msg = sprintf('Update of %s [%s] returned false', $model, $entity->id());
				$data = compact('values', 'conditions', 'model');
				return false;
			}
			$entity->set($values);
			return true;
		});
	}

	/**
	 * undeletes a record, in case it was marked as deleted
	 *
	 * @param object $entity current instance
	 * @return boolean true on success, false otherwise
	 */
	public function undelete($entity) {
		unset($entity->deleted);
		return is_null($entity->deleted) && $entity->save();
	}

	/**
	 * fetches the associated configuration record
	 *
	 * @param object $entity current instance
	 * @param string $field what field (in case of array) to return
	 * @param array $options an array of options currently supported are
	 *              - `default` : what to return, if nothing is found
	 *              - `flat`    : to flatten the result, if object/array-ish, defaults to false
	 * @return mixed configuration value
	 */
	public function configuration($entity, $field = null, array $options = array()) {
		if (empty($entity->config_id)) {
			return null;
		}
		$config = Configurations::load($entity->config_id);
		if (!$config) {
			return null;
		}
		return $config->val($field, $options);
	}

	/**
	 * fetches associated records
	 *
	 * {{{
	 *   $post->resolve('user'); // returns user, as defined in $post->user_id
	 * }}}
	 *
	 * @param object $entity current instance
	 * @param string|array $name name of model to load
	 * @param array $options an array of options currently supported are
	 *              - `resolver` : closure that takes $name as parameter and returns full qualified
	 *                 model name.
	 * @return array foreign object data
	 */
	public function resolve($entity, $fields = null) {
		$resolver = function($name) {
			$modelname = Inflector::pluralize(Inflector::classify($name));
			return Libraries::locate('models', $modelname);
		};
		$defaults = compact('resolver');
		$options += $defaults;

		switch (true) {
			case is_string($fields):
				$fields = array((stristr($fields, '_id')) ? $fields : "{$fields}_id");
				break;
			case empty($fields):
				$fields = self::fields();
				break;
			case is_array($fields):
				$fields = array_map(function($field){
					return (stristr($field, '_id')) ? $field : "{$field}_id";
				}, $fields);
				break;
		}

		$result = array();
		foreach ($fields as $field) {
			if (!preg_match('/^(.+)_id$/', $field, $matches)) {
				continue;
			}
			list($attribute, $name) = $matches;
			$model = $options['resolver']($name);
			if (empty($model)) {
				continue;
			}
			$foreign_id = (string) $entity->$attribute;
			if (!$foreign_id) {
				continue;
			}
			$result[$name] = $model::load($foreign_id);
		}
		return (count($fields) > 1) ? $result : array_shift($result);
	}

	/**
	 * allows easy output of IniFormat into a property
	 *
	 * @param object $entity instance of current Record
	 * @param string $field name of property to retrieve data for
	 * @return array an empty array in case of errors or the saved data decoded
	 * @filter
	 */
	public function _ini($entity, $field) {
		$params = compact('entity', 'field');
		return $this->_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			if (empty($entity->$field)) {
				return array();
			}
			$data = IniFormat::parse($entity->$field);
			if (!is_array($data)) {
				return array();
			}
			return $data;
		});
	}

}

?>