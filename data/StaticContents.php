<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\data;

use radium\data\Converter;

use SplFileInfo;
use DirectoryIterator;

class StaticContents extends \lithium\core\StaticObject {

	/**
	 * defines, where to search for static-files
	 *
	 * @var string
	 */
	public static $path = '/static';

	/**
	 * returns parsed content for given name.
	 *
	 * Content can be of type file, then this file will get parsed with Converter
	 * If content is a folder, instead an array with each filename as key and the converted
	 * content of that file as value is returned.
	 *
	 * @see radium\data\Converter::available()
	 * @see radium\data\StaticContents::available()
	 * @param string $name optional, if given parsed structure for given content is returned
	 * @param array $options additional options
	 * @return bool|array parsed structure as array or false in case of errors
	 */
	public static function get($name = null, array $options = array()) {
		$defaults = array('path' => dirname(LITHIUM_APP_PATH) . static::$path);
		$options += $defaults;

		$available = static::available($options);
		if ($available === false) {
			return false;
		}

		if ($name === null) {
			return $available;
		}

		if (stristr($name, '.')) {
			list($name, $field) = explode('.', $name, 2);
		} else {
			$field = null;
		}

		if (!in_array($name, $available)) {
			return false;
		}

		$file = sprintf('%s/%s', $options['path'], $name);
		if (is_file($file)) {
			return static::read($file, $field, $options);
		}

		$options['path'] = $file;
		$files = static::available($options);

		if ($files === false) {
			return false;
		}

		if (!empty($field)) {
			if (!in_array($field, $files)) {
				return false;
			}
			$filename = sprintf('%s/%s', $options['path'], $field);
			return static::read($filename, null, $options);
		}

		$result = array();

		foreach($files as $file) {
			$filename = sprintf('%s/%s', $options['path'], $file);
			$result[$file] = static::read($filename, $field, $options);
		}
		if (!empty($field)) {
			return false;
		}
		return $result;
	}

	/**
	 * return rendered content of a file
	 *
	 * @param string $file full path to file
	 * @param array $options additional options
	 *        - `field`: if rendered content is of type array and field is within, this is returned
	 *        - `render`: set to false to return un-rendered content from file
	 * @return mixed whatever the content may be
	 */
	public static function read($file, $data = array(), array $options = array()) {
		$defaults = array('field' => null, 'render' => true);
		$options += $defaults;

		$object = new SplFileInfo($file);
		if ($object->isDir()) {
			return false;
		}
		$type = $object->getExtension() ? : 'neon';
		return ($options['render'])
			? Converter::get($type, file_get_contents($file), $data, $options)
			: file_get_contents($file);
	}


	/**
	 * returns all available contents
	 *
	 * contents can be a file or folder.
	 *
	 * @param array $options additional options
	 *        - `path`: where to look for files, defaults to the content
	 *          of static property `$path`, relative to current app root
	 *        - `raw`: set to true to return SplFileInfo objects for each item
	 * @return array an array containing all available content files
	 */
	public static function available($options = array()) {
		$defaults = array('path' => dirname(LITHIUM_APP_PATH) . static::$path, 'raw' => false);
		$options += $defaults;

		if (!is_dir($options['path'])) {
			return false;
		}

		$it = new DirectoryIterator($options['path']);
		$result = array();
		foreach ($it as $file) {
			if (!$it->isDot()) {
				$result[] = ($options['raw']) ? $file : $file->getFilename();
			}
		}
		natsort($result);
		return array_values($result);
	}
}

?>