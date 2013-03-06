<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\extensions\errors\IniFormatException;
use lithium\util\Set;
use Exception;

class IniFormat {

	/**
	 * parses an ini-style string into an array
	 *
	 * when entering data into ini-style input fields, make sure, that you comply with the following
	 * rules (read up on http://php.net/parse_ini_string):
	 *
 	 * There are reserved words which must not be used as keys for ini files. These include:
 	 *
 	 *  `null`, `yes`, `no`, `true`, `false`, `on`, `off` and `none`
 	 *
 	 * Values `null`, `no` and `false` results in "", `yes` and `true` results in "1".
 	 * Characters ?{}|&~![()^" must not be used anywhere in the key and have a special meaning
 	 * in the value. So better not use them.
 	 *
	 * @see http://php.net/parse_ini_string
	 * @see lithium\util\Set::expand()
	 * @param string|array $data the string to be parsed, or an array thereof
	 * @param array $options an array of options currently supported are
	 *        - `default` : what to return, if nothing is found, defaults to an empty array
	 *        - `process_sections` : to enable process_sections, defaults to true
	 *        - `scanner_mode` : set scanner_mode to something different than INI_SCANNER_NORMAL
	 * @return array an associative, eventually multidimensional array or the `default` option.
	 */
	public static function parse($data = null, array $options = array()) {
		$defaults = array(
			'default' => array(),
			'scanner_mode' => INI_SCANNER_NORMAL,
			'process_sections' => true,
		);
		$options += $defaults;
		if (empty($data)) {
			return $options['default'];
		}
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = static::parse($value, $options);
			}
			return $data;
		}
		$raw = parse_ini_string(static::filter($data), $options['process_sections'], $options['scanner_mode']);
		if (empty($raw)) {
			return $options['default'];
		}
		try {
			$result = Set::expand($raw);
		} catch(Exception $e) {
			$error = $e->getMessage();
			$e = new IniFormatException(sprintf('IniFormat Error: %s', $error));
			$e->setData(compact('data', 'raw', 'options'));
			throw $e;
		}
		return $result;
	}

	/**
	 * filters all lines that are not parsable by parse_ini_string to avoid problems
	 *
	 * @see radium\util\IniFormat::_filter()
	 * @param string $data the ini-string to be inspected
	 * @return string $data a filtered ini-string that should pass parse_ini_string without errors
	 */
	public static function filter($data) {
		return implode("\n", array_filter(explode("\n", $data), array(__CLASS__, '_filter')));
	}

	/**
	 * whitelist all lines to make sure, no nonsense gets in
	 *
	 * all lines, that do not comply with our rules will be filtered, rules are:
	 *
	 * lines with a = must have keys, that only contains alphanumeric letters and -_. and space.
	 * lines starting with [ containing alphanumeric letters and end with ] also pass
	 *
	 * @see radium\util\IniFormat::parse()
	 * @see radium\util\IniFormat::filter()
	 * @param string $line The line to be inspected
	 * @return bool true, if line passes standards, false otherwise
	 */
	protected static function _filter($line) {
		if (preg_match('/^[ \t]*([a-zA-Z0-9\_\-\. ]*)[ \t]*=[ \t]*(.*)[ \t]*$/', $line)) {
			return true;
		}
		if (preg_match('/^[ \t]*\[([a-zA-Z0-9\_\-\.]*)\][ \t]*/', $line)) {
			return true;
		}
		return false;
	}

}

?>