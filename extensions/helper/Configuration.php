<?php
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
