<?php
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
namespace radium\models;

use radium\models\Configurations;

use lithium\core\Libraries;
use lithium\util\Inflector;

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
	public static $__finders = array(
		'deleted' => array(
			'conditions' => array(
				'deleted' => 'IS NOT NULL',
			)
		)
	);

	/**
	 * Required by lithium
	 *
	 * @see lithium\data\Model
	 * @return void
	 */
	public static function __init() {
		static::_isBase(__CLASS__, true);
		parent::__init();

		$finders = array_merge(static::$_status, static::$_types, static::$__finders);
		foreach (static::$_status as $key => $value) {
			if (is_numeric($key)) $key = $name;
			static::finder($key, array('conditions' => array('status' => $key)));
		}

		foreach (static::types() as $key => $value) {
			if (is_numeric($key)) $key = $name;
			static::finder($key, array('conditions' => array('type' => $key)));
		}

		// auto-update the created and updated fields
		static::applyFilter('save', function ($self, $params, $chain) {
			$field = ($params['entity']->exists()) ? 'updated' : 'created';
			$params['entity']->$field = date(DATE_ATOM);
			return $chain->next($self, $params, $chain);
		});

		// soft-delete on all rows, that have a 'deleted' field in schema
		static::applyFilter('delete', function ($self, $params, $chain) {
			$deleted = $params['entity']->schema('deleted');
			if(is_null($deleted)) {
				return $chain->next($self, $params, $chain);
			}
			$params['entity']->deleted = date(DATE_ATOM);
			return $params['entity']->save();
		});
	}

	/**
	 * all types for current model
	 *
	 * @return array all types with keys and their name
	 */
	public static function types() {
		return static::$_types;
	}

	/**
	 * all status for current model
	 *
	 * @return array all status with keys and their name
	 */
	public static function status() {
		return static::$_status;
	}

	/**
	 * generates an array that perfectly fits the form helpers select format
	 *
	 * @param string $name what field to display as name field
	 * @param string $order on what field to group results, defaults to status
	 * @return array a list, suitable for dropdowns, with the id as primary key
	 */
	public static function dropdown($name = 'name', $order = 'status') {
		$data = static::all();
		$key = static::key();
		return Set::combine($data->data(), "/$key", "/$name", "/$order");
	}

	/**
	 * Allows to pass in a query to do, what a man needs to do.
	 * Make sure, you are not trying to be james bond, without
	 * beeing sure, you know what you are doing.
	 *
	 * Returns a lithium\data\source\database\adapter\my_sql\Result object
	 *
	 * @param string $sql
	 * @return object
	 */
	public static function query($sql) {
		return static::connection()->invokeMethod('_execute', array($sql));
	}

	/**
	 * Returns all schema-fields, without their types
	 *
	 * @return array
	 */
	public static function fields() {
		return array_keys(static::schema());
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
	 * fetches the associated config record
	 *
	 * @param object $entity current instance
	 * @return array client data
	 */
	public function configuration($entity) {
		return $entity->config = Configurations::first($entity->config_id);
	}

	/**
	 * fetches the associated record
	 *
	 * @param object $entity current instance
	 * @param string|array $name name of model to load
	 * @return array remote object data
	 */
	public function resolve($entity, $fields = null) {
		$get_class = function($name) {
			$modelname = Inflector::pluralize(Inflector::classify($name));
			return Libraries::locate('models', $modelname);
		};

		switch (true) {
			case is_string($fields):
				$fields = array("{$fields}_id");
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

		$return = true;
		foreach ($fields as $field) {
			if (preg_match('/^(.+)_id$/', $field, $matches)) {
				list($attribute, $name) = $matches;
				$model = $get_class($name);
				if (empty($model)) {
					$entity->$name = null;
					continue;
				}
				$return = $entity->$name = $model::first((string) $entity->$attribute);
			}
		}
		return $return;
	}

	/**
	 * fetches the associated configuration
	 *
	 * @param object $entity current instance
	 * @param string $field name of configuration to return
	 * @return array client data
	 */
	public function value($entity, $field = null, array $options = array()) {
		$entity->config = Configurations::first($entity->config_id);
		return $entity->val($field, $options);
	}

}

?>