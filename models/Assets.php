<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\media\Mime;
use radium\data\Converter;

use lithium\util\Set;
use lithium\util\Inflector;
use lithium\action\Response;

use Exception;

class Assets extends \radium\models\BaseModel {

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'default' => 'default',
		'import' => 'Import',
		'plain' => 'Plain',
		'data' => 'Data',
		'audio' => 'Audio',
		'video' => 'Video',
		'image' => 'Image',
	);

	/**
	 * Stores the data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'md5' => array('type' => 'string'),
		'filename' => array('type' => 'string'),
		'mime' => array('type' => 'string'),
		'extension' => array('type' => 'string'),
		'size' => array('type' => 'int'),
	);

	protected $_meta = array(
		'source' => 'fs.files',
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validates = array(
		'slug' => array(
			array('notEmpty', 'message' => 'Please provide a valid slug'),
		),
		'md5' => array(
			array('notEmpty', 'message' => 'an md5 is required.'),
			array('md5', 'message' => 'md5 hash must be a valid md5 hash, doh.'),
		),
		'filename' => array(
			array('notEmpty', 'message' => 'a name is required.'),
		),
		'extension' => array(
			array('notEmpty', 'message' => 'an extension is required.'),
		),
		'size' => array(
			array('notEmpty', 'message' => 'a size is required.'),
			array('numeric', 'message' => 'size must be numeric.'),
		),
	);

	/**
	 * must be called with an array with the following fields to create an asset:
	 *
	 *	Array (
	 *		'name' => 'foobar'   // Name of file to be humanized for records name
	 *		'type' => 'jpg'      // file-extension, will be adapted to correct asset type
	 *		'tmp_name' => '/dir/some-file.jpg'  // FQDN of file, to be retrieved
	 * 		'size' => 44		 // optional, size in bytes of file
	 *	),
	 *
	 * @param array $file array as described above
	 * @param array $options additional options
	 *        - `type`: overwrite type of file, if you want to disable automatic detection
	 *        - `delete`: triggers deletion of retrieved temporary file, defaults to true
	 *        - `keep`: triggers keeping temporary files in case of errors, defaults to true
	 * @return array parsed content of Assets bytes
	 */
	public static function init($file, array $options = array()) {
		$defaults = array('type' => 'default', 'delete' => true, 'keep' => true);
		$options += $defaults;

		// fetch file, if remote
		// determine size of file on its own
		// determine md5 of file
		// find by md5, first

		$md5 = md5_file($file['tmp_name']);
		$asset = static::findByMd5($md5, array('fields' => '_id'));
		if ($asset) {
			if ($options['delete']) {
				unlink($file['tmp_name']);
			}
			$error = 'Asset already present';
			return compact('error', 'asset');
		}

		$mime = Mime::type($file['type']);
		if (is_array($mime)) {
			$mime = reset($mime);
		}
		$data = array(
			'name' => Inflector::humanize($file['name']),
			'filename' => sprintf('%s.%s', $file['name'], $file['type']),
			'slug' => strtolower(sprintf('%s.%s', $file['name'], $file['type'])),
			'md5' => $md5,
			'extension' => $file['type'],
			'type' => static::mimetype($mime),
			'mime' => $mime,
			'size' => $file['size'],
			'file' => file_get_contents($file['tmp_name']), //TODO: convert to stream
		);

		try {
			$asset = static::create($data);
			if ($asset->validates()) {
				$file['success'] = (bool) $asset->save();
				$file['asset'] = $asset;
			} else {
				$file['errors'] = $asset->errors();
			}

		} catch(Exception $e) {
			// return array('error' => 'asset could not be saved.');
			$file = array('error' => $e->getMessage());
		}
		if (!empty($file['success']) && empty($file['error']) && !$options['keep']) {
			unlink($file['tmp_name']);
		}
		return $file;
	}

	/**
	 * load a specific asset
	 *
	 * if just given a name, it returns the content body of that asset.
	 *
	 * @see radium\model\Assets::body()
	 * @param string $name name of asset to retrieve
	 * @param array $options an array of options, currently all of
	 *              Contentes::body() are supported, see there.
	 * @return mixed
	 */
	public static function get($name, $data = null, array $options = array()) {
		$defaults = array('default' => '', 'status' => 'active');
		$options += $defaults;
		$entity = static::load($name);
		if (!$entity || $entity->status != $options['status']) {
			return $options['default'];
		}
		return $entity->body($data, $options);
	}

	/**
	 * renders given asset
	 *
	 * @see radium\media\Mime
	 * @param object $asset instance of current record
	 * @param array $data additional data to be passed into render context
	 * @param array $options additional options
	 *        - `response`: returns prepared response, defaults to false
	 *                      WARNING: will load whole file into RAM at once.
	 *        - `download`: specify a filename and file will be offered as download
	 *        - `stream`: directly stream contents out, defaults to true
	 *        - `exit`: will exit process after streaming, defaults to true
	 * @return object|void returns response or directly renders the asset
	 */
	public function render($asset, $data = array(), array $options = array()) {
		$defaults = array(
			'download' => false,
			'response' => false,
			'stream' => true,
			'exit' => true,
			'size' => 51200,
		);
		$options += $defaults;
		if ($options['response'] || !$options['stream']) {
			return new Response(array(
				'headers' => array('Content-type' => $asset->mimetype),
				'body' => $asset->file->getBytes()
			));
		}

		Mime::header($asset->extension, $options);
		$stream = $asset->file->getResource();
		while (!feof($stream)) {
			echo fread($stream, $options['size']);
		}
		if ($options['exit']) {
			exit;
		}
	}

	/**
	 * returns decoded content of asset
	 *
	 * @see radium\data\Converter::get()
	 * @param object $asset instance of current record
	 * @param array $data additional data to be passed into render context
	 * @param array $options additional options to be passed into `Converter::get()`
	 * @return array parsed content of Assets bytes
	 */
	public function decode($asset, $data = array(), array $options = array()) {
		return Converter::get($asset->type, $asset->file->getBytes(), $data, $options);
	}

	/**
	 * runs whatever is suited for given type
	 *
	 * @param object $asset instance of current record
	 * @param array $options additional options to be passed into corresponding method.
	 * @return mixed
	 */
	public function run($asset, array $options = array()) {
		switch ($asset->type) {
			case 'import':
				return $asset->import($options);
		}
		return true;
	}

	/**
	 * imports data attribute into database. Allows importing of all model data within one file.
	 * Will call a method on each model, named `bulkImport` (can be customized) to do the heavy
	 * lifting of import. Thus, it allows overwriting the import on a per-model basis.
	 *
	 * The data of the asset, to be decoded must have the following structure:
	 *
	 *	Array (
	 *		'radium\\models\\Contents' => Array (
	 *			'5328587a4eaa3af84e000000' => Array (
	 *				'key' => 'value'   // all data per model
	 *			),
	 *			'5428587a4eaa3af84e000001' => Array (
	 *				'key' => 'value'   // all data per model
	 *			),
	 *		),
	 *
	 * @see radium\data\BaseModel::bulkImport()
	 * @param object $asset instance of current record
	 * @param array $options additional options, will be passed into bulkImport method.
	 *        - `function`: pass in a callback to handle the import of each record on the
	 *                      corresponding model class, defaults to `bulkImport`.
	 * @return array parsed content of Assets bytes
	 */
	public function import($asset, array $options = array()) {
		$defaults = array('function' => 'bulkImport');
		$options += $defaults;

		$data = $asset->decode();
		$result = array();

		list($temp, $params) = Set::slice($options, array('dry', 'prune', 'overwrite', 'strict'));

		$models = array_keys($data);
		foreach($models as $model) {
			$items = &$data[$model];
			$result[$model] = call_user_func(array($model, $options['function']), $items, $params);
		}
		return $result;
	}

	/**
	 * returns correct type for given mime-type to allow for grouping assets into types
	 *
	 * @param string $mime string of mime-type, e.g. application/json, image/png, ...
	 */
	public static function mimetype($mime) {
		if ($mime == 'application/json') {
			return 'import';
		}
		return substr($mime, 0, strpos($mime, '/'));
	}


}

?>