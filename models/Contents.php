<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

class Contents extends \radium\models\BaseModel {

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'page' => 'page',
		'post' => 'post',
		'news' => 'news',
		'wiki' => 'wiki',
		'faq' => 'faq',
		'term' => 'term',
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
		'type' => array('type' => 'string', 'default' => 'page'),
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


	public $_finders = array(
		'pages' => array(
			'conditions' => array('type' => 'page'),
			'order' => array('slug' => 'ASC'),
		),
		'posts' => array(
			'conditions' => array('type' => 'post'),
			'order' => array('created' => 'DESC'),
		),
		'news' => array(
			'conditions' => array('type' => 'news'),
			'order' => array('created' => 'DESC'),
		),
		'wikis' => array(
			'conditions' => array('type' => 'wiki'),
			'order' => array('title' => 'ASC'),
		),
		'faqs' => array(
			'conditions' => array('type' => 'faq'),
			'order' => array('title' => 'ASC'),
		),
		'terms' => array(
			'conditions' => array('type' => 'term'),
			'order' => array('title' => 'ASC'),
		),
	);
}

?>