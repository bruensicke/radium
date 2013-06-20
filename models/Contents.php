<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\data\Converter;

class Contents extends \radium\models\BaseModel {

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'plain' => 'Plain text',
		'html' => 'Html Markup',
		'mustache' => 'Mustache',
		'markdown' => 'Markdown',
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
		'type' => array('type' => 'string', 'default' => 'plain'),
		'body' => array('type' => 'string'),
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

	public function body($content, $data = array(), array $options = array()) {
		return Converter::get($content->type, $content->body, $data, $options);
	}

}

?>