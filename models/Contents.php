<?php

namespace radium\models;

use lithium\util\Set;

class Contents extends \radium\models\BaseModel {

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
		'type' => array(
			array('notEmpty', 'message' => 'type is empty.'),
			array('inList',
				'list' => array('page', 'post', 'news', 'wiki', 'faq', 'term'),
				'message' => 'type must be valid.'
			)
		),
		'name' => array(
			array('notEmpty', 'message' => 'a name is required.'),
		),
		'slug' => array(
			array('notEmpty', 'message' => 'a valid slug is required.', 'last' => true),
			array('alphaNumeric', 'message' => 'only numbers and letters are allowed.', 'last' => true),
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
		'page' => 'page',
		'post' => 'post',
		'news' => 'news',
		'wiki' => 'wiki',
		'faq' => 'faq',
		'term' => 'term',
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

	public static function dropdown() {
		$configurations = static::find('array');
		return Set::combine($configurations->data(), '/_id', '/name', '/status');
	}

}

?>