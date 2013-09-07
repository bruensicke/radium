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
	 * holds configuration per adapter
	 *
	 * @var array
	 */
	protected static $_configurations = array(
		'array' => array(
			'adapter' => 'Ini',
		),
		'ini' => array(
			'adapter' => 'Ini',
		),
		'json' => array(
			'adapter' => 'Json',
		),
		'neon' => array(
			'adapter' => 'Neon',
		),
		'plain' => array(
			'adapter' => 'Plain',
		),
		'html' => array(
			'adapter' => 'Html',
		),
		'mustache' => array(
			'adapter' => 'Mustache',
		),
		'markdown' => array(
			'adapter' => 'Markdown',
		),
	);

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

	/**
	 * A stub method called by `_config()` which allows `Adaptable` subclasses to automatically
	 * assign or auto-generate additional configuration data, once a configuration is first
	 * accessed. This allows configuration data to be lazy-loaded from adapters or other data
	 * sources.
	 *
	 * @param string $name The name of the configuration which is being accessed. This is the key
	 *               name containing the specific set of configuration passed into `config()`.
	 * @param array $config Contains the configuration assigned to `$name`. If this configuration is
	 *              segregated by environment, then this will contain the configuration for the
	 *              current environment.
	 * @return array Returns the final array of settings for the given named configuration.
	 */
	protected static function _initConfig($name, $config) {
		$defaults = array('adapter' => ucwords($name), 'filters' => array());
		return (array) $config + $defaults;
	}

}

?>