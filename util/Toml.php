<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use lithium\core\Libraries;
use Toml\Parser as TomlParser;

class Toml {

	public static function decode($content) {
		return TomlParser::fromString($content);
	}

	public static function file($file) {
		if (!file_exists($file)) {
			return array();
		}
		return TomlParser::fromFile($file);
	}
}

