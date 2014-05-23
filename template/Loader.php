<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\template;

use lithium\util\String;
use lithium\core\Libraries;

/**
 * View adapter for internal Handlebars templating.
 *
 * @see lithium\template\view\Renderer
 */
class Loader extends \lithium\core\Object {

	/**
	 * Returns the template paths.
	 *
	 * @param mixed $type
	 * @param array $params
	 * @return mixed
	 */
	public function template($type, array $params = array()) {
		if (!isset($this->_config['paths'][$type])) {
			return null;
		}

		$library = Libraries::get(isset($params['library']) ? $params['library'] : true);
		$params['library'] = $library['path'];

		return array_map(function ($item) use ($params) {
			return String::insert($item, $params);
		}, (array) $this->_config['paths'][$type]);
	}
}

?>