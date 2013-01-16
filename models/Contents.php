<?php

namespace radium\models;

use lithium\util\Set;

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

	/**
	 * load a specific configuration, or retrieve a field from one.
	 *
	 * if just given a name, it returns the val() of that record. If you
	 * pass in a default, you will return that, if nothing is found.
	 * You can also use $options to request a certain field, see val()
	 *
	 * @param string $name name of configuration to retrieve
	 * @param string $default what to return, if nothing is found
	 * @param array $options an array of options
	 * @return mixed
	 */
	public static function get($name, $default = null, array $options = array()) {
		$defaults = array('default' => $default, 'field' => null, 'status' => 'active');
		$options += $defaults;
		$entity = static::loadBySlug($name);
		if (!$entity || $entity->status != $options['status']) {
			return $options['default'];
		}
		return $entity->val($options['field']);
	}


}

?>