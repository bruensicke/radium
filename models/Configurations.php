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
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'boolean' => 'boolean',
		'string' => 'string',
		'list' => 'list',
		'array' => 'array',
	);

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
		'name' => array(
			array('notEmpty', 'message' => 'a name is required.'),
		),
		'slug' => array(
			array('notEmpty', 'message' => 'a valid slug is required.', 'last' => true),
			array('slug', 'message' => 'only numbers, small letters and . - _ are allowed.', 'last' => true),
		),
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
		$defaults = array('default' => null, 'flat' => false, 'field' => null);
		$options += $defaults;
		switch ($entity->type) {
			case 'boolean':
				return (boolean) $entity->value;
			case 'string':
				return (string) $entity->value;
			case 'list':
				$items = explode("\n", $entity->value);
				$result = array();
				foreach($items as $item) {
					$item = trim($item);
					if (!empty($item)) {
						$result[] = $item;
					}
				}
				return $result;
			case 'array':
				$config = Set::expand(parse_ini_string($entity->value));
				$field = $options['field'];
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
				return ($options['flat'])
					? Set::flatten($config)
					: $config;
		}
		return $options['default'];
	}

	/**
	 * load a specific configuration, or retrieve a field from one.
	 *
	 * if just given a name, it returns the val() of that record. If you
	 * pass in a default, you will return that, if nothing is found.
	 * You can also use $options to request a certain field, see val()
	 *
	 * @see sy_core\model\Configs::val()
	 * @param string $name name of configuration to retrieve
	 * @param string $default what to return, if nothing is found
	 * @param array $options an array of options, currently all of
	 *              Configs::val() are supported, see there.
	 * @return mixed
	 */
	public static function get($name, $default = null, array $options = array()) {
		$defaults = array('default' => $default, 'field' => null, 'status' => 'active');
		$options += $defaults;
		$entity = static::slug($name);
		if (!$entity || $entity->status != $options['status']) {
			return $options['default'];
		}
		return $entity->val($options);
	}

}

?>