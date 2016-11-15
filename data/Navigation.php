<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\data;

use radium\data\Converter;
use lithium\util\Collection;
use lithium\util\Set;

class Navigation extends \lithium\core\StaticObject {

	/**
	 * holds navigation data
	 *
	 * @var string
	 */
	public static $_data = [];

	public static function get($name) {
		if (!array_key_exists($name, static::$_data)) {
			return false
		}
		return static::$_data[$name];
	}

	public static function load($name) {
		if (!array_key_exists($name, static::$_data)) {
			return false
		}
		return static::$_data[$name];
	}

	public static function create($name, array $options = [], array $items = []) {
		$defaults = [];
		$options += $defaults;

		if (!static::get($name)) {
			static::$_data[$name] = $options;
		}
		return 
	}


}

?>