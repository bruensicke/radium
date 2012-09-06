<?php

namespace radium\models;

use lithium\util\Set;

/**
 * Works as a key-value store with db-backend
 *
 * Use like this:
 *
 * Configurations::write('title', 'Application'); // writes Application to title
 * Configurations::read('title', 'unnamed App'); // 'unnamed App' will be default fallback
 *
 */
class Configurations extends \radium\models\BaseModel {

	/**
	 * Stores the data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'name' => array('type' => 'string', 'default' => '', 'null' => false),
		'slug' => array('type' => 'string', 'default' => '', 'null' => false),
		'type' => array('type' => 'string', 'default' => 'string'),
		'value' => array('type' => 'string'),
		'data' => array('type' => 'array'),
		'notes' => array('type' => 'string', 'default' => '', 'null' => false),
		'status' => array('type' => 'string', 'default' => 'active', 'null' => false),
		'created' => array('type' => 'datetime', 'default' => '', 'null' => false),
		'updated' => array('type' => 'datetime'),
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
		'type' => array(
			array('notEmpty', 'message' => 'type is empty.'),
			array('inList', 'list' => array('boolean', 'string', 'array'), 'message' => 'type must be valid.')
		),
		'name' => array(
			array('notEmpty', 'message' => 'a name is required.'),
		),
		'slug' => array(
			array('notEmpty', 'message' => 'a valid slug is required.', 'last' => true),
			array('slug', 'message' => 'only numbers, small letters and . - _ are allowed.', 'last' => true),
		),
		'status' => array(
			array('notEmpty', 'message' => 'Status is empty.'),
			array('inList', 'list' => array('active', 'inactive'), 'message' => 'Status must be a valid option.')
		)
	);

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'boolean' => 'boolean',
		'string' => 'string',
		'array' => 'array',
	);

	/**
	 * Sets default connection options and connects default finders.
	 *
	 * @see lithium\data\Model::__init()
	 * @param array $options
	 * @return void
	 */
	public static function __init(array $options = array()) {
		parent::__init($options);
	}

	/**
	 * return configured value
	 *
	 * Depending on type this can be a boolean, a string or an array.
	 * For array, this can be used to retrieve sub-key of array, like this:
	 *
	 * val(array('field' => 'parentkey'))
	 * val(array('field' => 'parentkey.subkey'))
	 *
	 * @see sy_core\model\Configs::get()
	 * @param object $entity instance of current Record
	 * @param array $options an array of options currently supported are
	 *              - `default` : what to return, if nothing is found
	 *              - `field`   : what field (in case of array) to return
	 *              - `type`    : to force a certain type, ie. boolean
	 * @return mixed whatever can be returned
	 */
	public function val($entity, $field = null, array $options = array()) {
		$defaults = array('default' => null);
		$options += $defaults;
		switch ($entity->type) {
			case 'boolean':
				return (boolean) $entity->value;
			case 'string':
				return (string) $entity->value;
			case 'array':
				$config = Set::expand(parse_ini_string($entity->value));
				if (!empty($field)) {
					if (array_key_exists($field, $config)) {
						return $config[$field];
					}
				}
				$field = '/'.str_replace('.', '/', $field).'/.';
				$result = current(Set::extract($config, $field));
				if (!empty($result)) {
					return $result;
				}
				return (array) $config;
		}
		return $options['default'];
	}

	public function flat($entity) {
		$config = Set::flatten($entity->val());
		$result = array();
		foreach ($config as $key => $value) {
			$result[] = compact('key', 'value');
		}
		return $result;
	}

	/**
	 * load a specific configuration, or retrieve a field from one.
	 *
	 * if just given a name, it returns the val() of that record. If you
	 * pass in a default, you will return that, if nothing is found.
	 * You can also use $options to request a certain field, see val()
	 *
	 * @see sy_core\model\Configs::val()
	 * @param string $name name of confiuration to retrieve
	 * @param string $default what to return, if nothing is found
	 * @param array $options an array of options, currently all of
	 *              Configs::val() are supported, see there.
	 * @return mixed
	 */
	public static function get($name, $default = null, array $options = array()) {
		$defaults = array('default' => $default, 'field' => null);
		$options += $defaults;
		$entity = static::first($name);
		if (!$entity || !$entity->status) {
			return $options['default'];
		}
		return $entity->val($options);
	}

	/**
	 * finds and loads configuration with given slug
	 *
	 * @param string $slug short unique string to identify configuration
	 * @param string $status status configuration must have
	 * @return object|boolean found configuration entity or false, if none found
	 * @filter
	 */
	public static function slug($slug, $status = 'active', array $options = array()) {
		$params = compact('slug', 'status', 'options');
		return static::_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			$options['conditions'] = compact('slug', 'status');
			$configuration = Configurations::find('first', $options);
			if (!$configuration) {
				return false;
			}
			return $configuration;
		});
	}

}

?>