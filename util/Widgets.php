<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\data\StaticContents;
use lithium\core\Libraries;
use lithium\util\Set;
use lithium\util\Collection;

class Widgets {

	public static function find(array $options = array()) {
		$defaults = array(
			'collect' => true,
		);
		$options += $defaults;

		$data = array();
		$libs = Libraries::get(null, 'path');
		$recursive = true;
		foreach($libs as $lib => $path) {
			$result = array();
			$path .= '/views/widgets';
			$files = StaticContents::available(compact('path', 'recursive'));
			if (!$files) {
				continue;
			}
			$temp = array_keys(Set::flatten($files, array('separator' => '/')));
			foreach ($temp as $key => $value) {
				if (strpos($value, 'admin.') !== false) {
					continue;
				}
				if (strpos($value, 'inc.') !== false) {
					continue;
				}
				$result[$key] = str_replace('.html.php', '', $value);
			}
			$data[$lib] = $result;
		}
		return $data;
	}

}

