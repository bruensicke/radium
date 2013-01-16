<?php
namespace radium\extensions\helper;

use radium\models\Contents;

class Contents extends \lithium\template\Helper {

	/**
	 * Retrieve information from Configurations
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function get($name, $default = null, array $options = array()) {
		$defaults = array('field' => null, 'status' => 'active');
		$options += $defaults;
		return Contents::get($name, $default, $options);
	}

}
