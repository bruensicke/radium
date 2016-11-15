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
		'plain' => 'Plain Text',
		'html' => 'Html Markup',
		'handlebars' => 'Handlebars Template',
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
		'accessible' => array('type' => 'bool'),
		'layout' => array('type' => 'string'),
		'body' => array('type' => 'string'),
	);

	/**
	 * load a specific contents
	 *
	 * if just given a name, it returns the body() of that record. If you
	 * pass in data, it will be used in the render context.
	 *
	 * @see radium\model\Contents::body()
	 * @param string $name name of configuration to retrieve
	 * @param array $data additional data to be passed into render context
	 * @param array $options an array of options, currently all of
	 *              Contents::body() are supported, see there.
	 * @return mixed
	 */
	public static function get($name, $data = null, array $options = array()) {
		$defaults = array('default' => '', 'status' => 'active');
		$options += $defaults;
		$entity = static::load($name);
		if (!$entity || $entity->status != $options['status']) {
			return $options['default'];
		}
		return $entity->body($data, $options);
	}

	/**
	 * returns parsed content of Contents body
	 *
	 * @see radium\data\Converter::get()
	 * @param object $content instance of current record
	 * @param array $data additional data to be passed into render context
	 * @param array $options additional options to be passed into `Converter::get()`
	 * @return array parsed content of Contents body
	 */
	public function body($content, $data = array(), array $options = array()) {
		return Converter::get($content->type, $content->body, $data, $options);
	}

}

?>
