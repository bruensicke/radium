<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\adapter\converters;

use radium\util\Neon as NeonFormatter;
use Neon\NeonException;
use Exception;

use lithium\util\Set;

class Neon extends \lithium\core\Object {

	/**
	 * returns rendered content
	 *
	 * @param string $content input content
	 * @param string $data field to retrieve from configuration
	 * @param array $options an array with additional options
	 * @return string content as given
	 * @filter
	 */
	public function get($content, $data = null, array $options = array()) {
		$defaults = array('default' => array(), 'flat' => false);
		$options += $defaults;
		$params = compact('content', 'data', 'options');
		return $this->_filter(__METHOD__, $params, function($self, $params) {
			extract($params);
			try {
				$config = NeonFormatter::decode($content);
			} catch(NeonException $e) {
				return $options['default'];
			} catch(Exception $e) {
				return $options['default'];
			}
			if (!empty($data) && is_scalar($data)) {
				if (array_key_exists($data, (array) $config)) {
					return $config[$data];
				}
			}
			$data = '/'.str_replace('.', '/', $data).'/.';
			$result = current(Set::extract((array) $config, $data));
			if (!empty($result)) {
				return $result;
			}
			return ($options['flat'])
				? Set::flatten($config)
				: $config;
		});
	}

}
?>