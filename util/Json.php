<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\extensions\errors\JsonException;

class JSON {

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
		switch (json_last_error()) {
			case JSON_ERROR_DEPTH:
				$error = 'Maximum stack depth exceeded';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'State mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Unexpected control character found';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON';
				break;
			case JSON_ERROR_UTF8:
				$error = 'Encoding error occured';
				break;
			case JSON_ERROR_NONE:
			default:
				$error = false;
		}
		if ($error) {
			$e = new JsonException(sprintf('JSON Error: %s', $error));
			$e->setData($json);
			throw $e;
		}
		return $result;
	}
}

?>