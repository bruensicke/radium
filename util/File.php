<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\data\Converter;
use lithium\core\Libraries;

class File extends \lithium\core\StaticObject {

	/**
	 * reads content from file, converting it if matching converter is found.
	 *
	 * This method is able to understand library-relative paths. If file starts with a valid
	 * library name followed by a slash, i.e. `radium/some-path/file.ext` it will find that file
	 * on its own within the file-system. It automatically detects the base-path for that library
	 * and will find the file within that library.
	 *
	 * @see radium\data\Converter::get()
	 * @param string $file filename to retrieve contents from
	 * @param array $data additional data to be put into `Converter::get()`
	 * @param array $options additional options to be put into `Converter::get()`
	 * @return mixed
	 */
	public static function contents($file, $data = array(), array $options = array()) {
		$defaults = array(
			'convert' => true,
			'type' => static::extension($file),
			'default' => false,
		);
		$options += $defaults;

		if (file_exists($file)) {
			$content = file_get_contents($file);
			return ($options['convert'])
				? Converter::get($options['type'], $content, $data, $options)
				: $content;
		}
		list($library, $filename) = explode('/', $file, 2);
		if (!$libraryPath = Libraries::get($library, 'path')) {
			return $options['default'];
		}
		$file = sprintf('%s/%s', $libraryPath, $filename);
		if (file_exists($file)) {
			$content = file_get_contents($file);
			return ($options['convert'])
				? Converter::get($options['type'], $content, $data, $options)
				: $content;
		}
		return $options['default'];
	}

	/**
	 * returns file-extension for given file
	 *
	 * @param string $file filename
	 * @return string extension of given file
	 */
	public static function extension($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

	/**
	 * returns base filename for given file without path or extension
	 *
	 * @param string $file filename
	 * @return string extension of given file
	 */
	public static function name($file) {
		return pathinfo($file, PATHINFO_FILENAME);
	}



}