<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\data;

class Converter extends \lithium\core\Adaptable {

	/**
	 * Libraries::locate() compatible path to adapters for this class.
	 *
	 * @see lithium\core\Libraries::locate()
	 * @var string Dot-delimited path.
	 */
	protected static $_adapters = 'adapter.converters';

	/**
	 * renders content with given configuration/adapter using data
	 *
	 * @param string $name The name of the `Parser` configuration
	 * @param array $data additional data to be passed into render context
	 * @param string $content content that needs to be rendered
	 * @param array $options Additional options to be forwarded into Adapters render method.
	 * @return string the rendered content
	 * @filter
	 */
	public static function get($name, $content = null, $data = array(), array $options = array()) {
		$defaults = array();
		$options += $defaults;
		$params = compact('name', 'content', 'data', 'options');
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			extract($params);
			return $self::adapter($name)->get($content, $data, $options);
		});
	}

}

?>