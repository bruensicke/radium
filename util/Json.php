<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\extensions\errors\JsonException;

class Json {

	/**
	 * JSON error codes
	 *
	 * @see http://php.net/json_last_error
	 * @var array
	 */
	public static $errors = array(
		JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
		JSON_ERROR_STATE_MISMATCH => 'State mismatch',
		JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
		JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
		JSON_ERROR_UTF8 => 'Encoding error occured'
	);

	/**
	 * encodes an object or array to json
	 *
	 * @param array|object $obj the object or array to be encoded
	 * @return string the generated json
	 */
	public static function encode($obj) {
		return json_encode($obj);
	}

	/**
	 * decodes a json string into an array or object
	 *
	 * @throws radium\extensions\errors\JsonException
	 * @param string $json the json string to be decoded
	 * @param boolean $assoc if false, returns an object instead of an array
	 * @param integer $depth how many nested objects/arrays to be returned
	 * @return array|object the decoded object or array
	 */
	public static function decode($json, $assoc = true, $depth = 512) {
		if (empty($json)) {
			return array();
		}
		$result = json_decode($json, $assoc, $depth);
		$errorCode = json_last_error();
		if ($errorCode == JSON_ERROR_NONE) {
			return $result;
		}
		$msg = (isset(static::$errors[$errorCode]))
			? static::$errors[$errorCode]
			: 'Unknown error occured';
		$e = new JsonException(sprintf('JSON Error: %s', $msg));
		$e->setData($json);
		throw $e;
	}
}

?>