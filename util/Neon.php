<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

use radium\util\File;

use lithium\core\Libraries;
use lithium\util\Collection;
use lithium\data\collection\DocumentSet;

use Neon\Neon as NeonRenderer;

class Neon {

	/**
	 * holds the renderer instance
	 *
	 * @var object
	 */
	public static $_renderer = null;

	/**
	 * controls where neon-files can reside inside a library and automatically be found
	 *
	 * @var string|array
	 */
	public static $_paths = array(
		'neons' => array('{:library}\data\{:class}\{:name}.neon'),
	);

	/**
	 * encodes given $input to neon format syntax
	 *
	 * @see radium\util\Neon::renderer()
	 * @param mixed $input content that will be converted into neon format
	 * @param array $options additional options to be passed into `encode()` method
	 * @return string the neon markup that represents given `$input`
	 */
	public static function encode($input, $options = null) {
		return static::renderer()->encode($input, $options);
	}

	/**
	 * decodes given $input from neon format into native php structure
	 *
	 * That will be most likely an array or a string.
	 *
	 * @see radium\util\Neon::renderer()
	 * @param string $input neon marku that will be converted into native php format
	 * @return mixed generated php structure derived from given `$input`
	 */
	public static function decode($content) {
		return static::renderer()->decode($content);
	}

	/**
	 * parses a given file and its content with neon parser and returns its data structure
	 *
	 * This method is able to understand library-relative paths. If filename starts with a valid
	 * library name followed by a slash, i.e. `radium/some-path/file.ext` it will find that file
	 * on its own within the file-system. It automatically detects the base-path for that library
	 * and will find the file within that library.
	 *
	 * @see radium\util\File::contents()
	 * @param string $file full path to file or a library-related path, starting with `$library/`
	 * @param string $field only return that field from loaded file or null if not present
	 * @return mixed generated php structure derived from neon-parsed file
	 */
	public static function file($file, $field = null) {
		return File::contents($file, $field, array('type' => 'neon'));
	}

	/**
	 * instantiates and returns instance of neon-renderer
	 *
	 * @return object instance of NeonRenderer
	 */
	public static function renderer() {
		if (is_null(static::$_renderer)) {
			Libraries::add('Neon', array('path' => RADIUM_PATH . '/libraries/neon'));
			static::$_renderer = new NeonRenderer();
		}
		return static::$_renderer;
	}

	/**
	 * Loads entities and records from file-based structure
	 *
	 * Trys to implement a similar method to load datasets not from database, but rather from
	 * a file that holds all relevant model data and is laid into the filesystem of each library.
	 * This allows for easy default-data to be loaded without using a database as backend.
	 *
	 * Put your files into `{:library}\data\{:class}\{:name}.neon` and let the content be found
	 * with loading just the id or slug of that file.
	 *
	 * Attention: This feature is considered experimental and should be used with care. It might
	 *            not work as expected. Also, not all features are implemented.
	 *
	 * If nothing is found, it just returns null or an empty array to ensure a falsey value.
	 *
	 * @param string|array $model fully namespaced model class, e.g. `radium\models\Contents`
	 *                     can also be an array, in which case `key` and `source` must be given
	 *                     according to the internal structure of `Model::meta()`.
	 * @param string $type The find type, which is looked up in `Model::$_finders`. By default it
	 *        accepts `all`, `first`, `list` and `count`. Later two are not implement, yet.
	 * @param array $options Options for the query. By default, accepts:
	 *        - `conditions`: The conditional query elements, e.g.
	 *                 `'conditions' => array('published' => true)`
	 *        - `fields`: The fields that should be retrieved. When set to `null`, defaults to
	 *             all fields.
	 *        - `order`: The order in which the data will be returned, e.g. `'order' => 'ASC'`.
	 *        - `limit`: The maximum number of records to return.
	 *        - `page`: For pagination of data.
	 * @return mixed returns null or an empty array if nothing is found
	 *               If `$type` is `first` returns null or the correct entity with given data
	 *               If `$type` is `all` returns null or a DocumentSet object with loaded entities
	 */
	public function find($model, $type, array $options = array()) {
		$paths = self::$_paths;
		Libraries::paths($paths);
		$meta = is_array($model) ? $model : $model::meta();
		$locate = sprintf('neons.%s', $meta['source']);
		$data = Libraries::locate($locate, null, array('namespaces' => true));
		$files = new Collection(compact('data'));
		unset($data);
		$files->each(function($file){
			return str_replace('\\', '/', $file).'neon';
		});

		extract($options);
		if (isset($conditions['slug'])) {
			$field = 'slug';
		}
		if (isset($conditions[$meta['key']])) {
			$field = $meta['key'];
		}
		if (!isset($field)) {
			return array();
		}
		$value = $conditions[$field];

		switch (true) {
			case is_string($value):
				$pattern = sprintf('/%s/', $value);
				break;
			case isset($value['like']):
				$pattern = $value['like'];
				break;
		}

		if (isset($pattern)) {
			$filter = function($file) use ($pattern) {
				return (bool) preg_match($pattern, $file);
			};
		}
		if (isset($filter)) {
			$files = $files->find($filter);
		}
		if (isset($order)) {
			// TODO: add sort
		}

		if ($type == 'count') {
			return count($files);
		}

		if ($type == 'list') {
			// TODO: implement me
		}

		if ($type == 'first' && count($files)) {
			$data = self::file($files->first());
			$data[$field] = $value;
			if ($model === 'radium\models\Configurations') {
				$data['value'] = Neon::encode($data['value']);
			}
			return $model::create($data);
		}

		if ($type == 'all' && count($files)) {
			$data = array();
			foreach ($files as $file) {
				$current = self::file($file);

				if (is_array($model)) {
					$filename = File::name($file);
					$data[$filename] = $current;
					break;
				}
				if ($model === 'radium\models\Configurations') {
					$current['value'] = Neon::encode($current['value']);
				}
				$data[] = $model::create($current);
			}
			if (is_array($model)) {
				return new Collection(compact('data'));
			}
			$model = $meta['class'];
			return new DocumentSet(compact('data', 'model'));
		}
		return false;
	}

}

?>