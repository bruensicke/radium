<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use lithium\core\Libraries;
use Neon\Neon as NeonRenderer;

class Neon {

	public static $_renderer = null;

	public static function encode($input, $options = null) {
		return static::renderer()->encode($input, $options);
	}

	public static function decode($content) {
		return static::renderer()->decode($content);
	}

	public static function file($file) {
		if (!file_exists($file)) {
			return array();
		}
		return static::renderer()->decode(file_get_contents($file));
	}

	public static function renderer() {
		if (is_null(static::$_renderer)) {
			Libraries::add('Neon', array('path' => RADIUM_PATH . '/libraries/neon'));
			static::$_renderer = new NeonRenderer();
		}
		return static::$_renderer;
	}

}

?>