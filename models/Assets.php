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
		'mimetype' => array('type' => 'string'),
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
	 *        - `stream`: directly stream contents out, defaults to true
	 *        - `exit`: will exit process after streaming, defaults to true
	 * @return object|void returns response or directly renders the asset
	 */
	public function render($asset, $data = array(), array $options = array()) {
		$defaults = array(
			'response' => false,
			'stream' => true,
			'exit' => true,
			'size' => 51200,
		);
		$options += $defaults;
		if ($options['response'] || !$options['stream']) {
			return new Response(array(
				'headers' => array('Content-type' => $asset->mimetype),
				'body' => $object->file->getBytes()
			));
		}

		Mime::header($asset->extension);
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

	public static function init($file, array $options = array()) {
		$defaults = array('type' => 'default');
		$options += $defaults;
		$md5 = md5_file($file['tmp_name']);
		// find by md5, first
		$data = array(
			'name' => Inflector::humanize($file['name']),
			'filename' => sprintf('%s.%s', $file['name'], $file['type']),
			'slug' => strtolower(sprintf('%s.%s', $file['name'], $file['type'])),
			'md5' => $md5,
			'extension' => $file['type'],
			'type' => $options['type'], // TODO: define type, aka image, video, audio
			'mimetype' => Mime::type($file['type']),
			'size' => $file['size'],
			'file' => file_get_contents($file['tmp_name']), //TODO: convert to stream
		);
		try {
			$asset = static::create($data);
			if ($asset->validates()) {
				$file['success'] = (bool) $asset->save();
				$file['id'] = $asset->id();
			} else {
				$file['errors'] = $asset->errors();
			}

		} catch(Exception $e) {
			// return array('error' => 'asset could not be saved.');
			$file = array('error' => $e->getMessage());
		}
		// TODO: remove uploaded file!
		return $file;
	}

}

?>