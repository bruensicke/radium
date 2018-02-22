<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\adapter\converters;

class Plain extends \lithium\core\DynamicObject {

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
		$defaults = array('stripHtml' => true);
		$options += $defaults;
		$params = compact('content', 'data', 'options');
		return $this->_filter(__METHOD__, $params, function($self, $params) {
			if (is_array($params['content'])) {
				return array_map(array(__CLASS__, 'render'), $params['content'], $params['options']);
			}
			if ($params['options']['stripHtml']) {
				return strip_tags($params['content']);
			}
			return $params['content'];
		});
	}

}
?>
