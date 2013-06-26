<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\adapter\converters;

use radium\util\Json as JsonFormatter;
use radium\extensions\errors\JsonException;
use Exception;

class Json extends \lithium\core\Object {

	/**
	 * returns rendered content
	 *
	 * @param string $content input content
	 * @param array $data additional data to be passed into render context
	 * @param array $options an array with additional options
	 * @return string content as given
	 * @filter
	 */
	public function get($content, $data = array(), array $options = array()) {
		$defaults = array('assoc' => true, 'depth' => 512, 'default' => array());
		$options += $defaults;
		$params = compact('content', 'data', 'options');
		return $this->_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			try {
				$config = JsonFormatter::decode($content, $options['assoc'], $options['depth']);
				// TODO: evaluate $data as $field-request
				return $config;
			} catch(JsonException $e) {
				return $options['default'];
			} catch(Exception $e) {
				return $options['default'];
			}
			return $options['default'];
		});
	}

}
?>