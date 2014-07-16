<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Contents;

class Content extends \lithium\template\Helper {

	/**
	 * returns body from Content
	 *
	 * @see radium\models\Contents::get()
	 * @param string $type
	 * @param array $data additional data to be passed into render context
	 * @param array $options an array of options, currently all of
	 *              Contents::body() are supported, see there.
	 * @return mixed
	 */
	public function get($name, $data = null, array $options = array()) {
		$defaults = array('field' => null, 'status' => 'active');
		$options += $defaults;
		return Contents::get($name, $data, $options);
	}

}
