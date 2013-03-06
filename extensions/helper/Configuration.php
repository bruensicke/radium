<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Configurations;

class Configuration extends \lithium\template\Helper {

	/**
	 * Retrieve information from Configurations
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function get($name, $default = null, array $options = array()) {
		$defaults = array('field' => null, 'status' => 'active');
		$options += $defaults;
		return Configurations::get($name, $default, $options);
	}

}
