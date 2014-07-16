<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\data\Converter;

class Pages extends \radium\models\BaseModel {

	/**
	 * Custom status options
	 *
	 * @var array
	 */
	public static $_layouts = array(
		'default' => 'default',
	);

	/**
	 * Custom status options
	 *
	 * @var array
	 */
	public static $_templates = array(
		'full' => 'full',
		'half' => 'half',
	);

	/**
	 * Stores the data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'parent_id' => array('type' => 'string'),
		'fullslug' => array('type' => 'string', 'default' => '', 'null' => false),
		'layout' => array('type' => 'select', 'default' => 'default', 'null' => false),
		'template' => array('type' => 'select', 'default' => 'full', 'null' => false),
		'body' => array('type' => 'rte'),
		'widgets' => array('type' => 'neon'),
	);

	/**
	* Validation rules
	*
	* @var array
	*/
	public $validates = array(
		'layout' => array(
			array('notEmpty', 'message' => 'a layout is required.'),
		),
		'template' => array(
			array('notEmpty', 'message' => 'a template is required.'),
			// array('length', 'message' => 'must be at least 10 chars.', 'limit' => 10),
		),
	);

	/**
	 * all layouts available to pages
	 *
	 * @param string $type type to look for
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function layouts($type = null) {
		return static::_group(__FUNCTION__, $type);
	}

	/**
	 * all templates available to pages
	 *
	 * @param string $type type to look for
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function templates($type = null) {
		return static::_group(__FUNCTION__, $type);
	}

	/**
	 * returns all widgets from current Page
	 *
	 * @see radium\data\Converter::get()
	 * @param object $entity instance of current record
	 * @param string $field returns a certain field from widgets
	 * @param array $options additional options to be passed into Converter::get()
	 * @return array an array of widgets
	 */
	public function widgets($entity, $field = null, array $options = array()) {
		return Converter::get('neon', $entity->widgets, $field, $options);
	}



}
