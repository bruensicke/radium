<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\models\Configurations;
use radium\util\Neon;
use radium\util\IniFormat;
use lithium\core\Libraries;
use lithium\core\Environment;
use lithium\util\Set;
use lithium\util\Validator;
use lithium\util\Inflector;
use lithium\util\StringDeprecated;

/**
 * Base class for all Models
 *
 * If you have models in your app, you should extend this class like that:
 *
 * {{{
 *  class MyModel extends \radium\models\BaseModel {
 * }}}
 *
 * @see app\models
 * @see lithium\data\Model
 */
class BaseModel extends \lithium\data\Model {

	/**
	 * Custom status options
	 *
	 * @var array
	 */
	public static $_status = array(
		'active' => 'active',
		'inactive' => 'inactive',
	);

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'default' => 'default',
	);

	/**
	 * Custom actions available on this object
	 *
	 * @var array
	 */
	protected static $_actions = array(
		'first' => array(
			'delete' => array('icon' => 'remove', 'class' => 'hover-danger', 'data-confirm' => 'Do you really want to delete this record?'),
			'export' => array('icon' => 'download'),
			'duplicate' => array('name' => 'clone', 'icon' => 'copy', 'class' => 'hover-primary'),
			'edit' => array('icon' => 'pencil2', 'class' => 'primary'),
		),
		'all' => array(
			'import' => array('icon' => 'upload'),
			'export' => array('icon' => 'download'),
			'add' => array('name' => 'create', 'icon' => 'plus', 'class' => 'primary'),
		)
	);

	/**
	 * Stores the minimum data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'config_id' => array('type' => 'configuration', 'null' => true),
		'name' => array('type' => 'string', 'default' => '', 'null' => false),
		'slug' => array('type' => 'string', 'default' => '', 'null' => false),
		'type' => array('type' => 'string', 'default' => 'default'),
		'status' => array('type' => 'string', 'default' => 'active', 'null' => false),
		'notes' => array('type' => 'string', 'default' => '', 'null' => false),
		'created' => array('type' => 'datetime', 'default' => '', 'null' => false),
		'updated' => array('type' => 'datetime'),
		'deleted' => array('type' => 'datetime'),
	);

	/**
	 * Criteria for data validation.
	 *
	 * @see lithium\data\Model::$validates
	 * @see lithium\util\Validator::check()
	 * @var array
	 */
	public $validates = array(
		'_id' => array(
			array('notEmpty', 'message' => 'a unique _id is required.', 'last' => true, 'on' => 'update'),
		),
		'name' => array(
			array('notEmpty', 'message' => 'This field should not be empty.'),
		),
		'slug' => array(
			array('notEmpty', 'message' => 'Insert an alphanumeric value'),
			array('slug', 'message' => 'This must be alphanumeric'),
		),
		'type' => array(
			array('notEmpty', 'message' => 'Please specify a type'),
			array('type', 'message' => 'Type is unknown. Please adjust.'),
		),
		'status' => array(
			array('notEmpty', 'message' => 'Please specify a status'),
			array('status', 'message' => 'Status is unknown. Please adjust.'),
		),
	);

	/**
	 * If this contains an array, the containing fields ar rendered as tabs in add/edit forms.
	 *
	 * 	$_renderLayout = array(
	 * 		'Tab1' => array(
	 * 			'field1',
	 * 			'field2',
	 * 			'field3'
	 * 		),
	 *		'Tab2' => array(
	 *			'field4',
	 * 			'field5'
	 *		),
	 * 	);
	 *
	 * @var array
	 */
	public static $_renderLayout = array();

	/**
	 * Custom find query properties, indexed by name.
	 *
	 * @var array
	 */
	public $_finders = array(
		'deleted' => array(
			'conditions' => array(
				'deleted' => array('>=' => 1),
			)
		)
	);

	/**
	 * Default query parameters.
	 *
	 * @var array
	 */
	protected $_query = array(
		'conditions' => array(
			'deleted' => null,
		),
	);


	/**
	 * Specifies all meta-information for this model class,
	 * overwritten to enable versions by default.
	 *
	 * @see lithium\data\Connections::add()
	 * @var array
	 */
	protected $_meta = array(
		'versions' => false,
		'neon' => true,
	);

	protected static $_rss = array(
		'title' => 'name',
		'description' => 'notes',
		'link' => 'http://{:host}/{:controller}/view/{:_id}',
		'guid' => '{:controller}/view/{:_id}',
	);

	/**
	 * overwritten to allow for soft-deleting a record
	 *
	 * The schema of the relevant model needs a field defined in schema called `deleted`.
	 * As soon as this is the case, the record does not get deleted right away but instead
	 * marked for deletion, i.e. setting a timestamp into the `deleted` field. Unless you make
	 * use of the `force` option, then the record will get deleted without further ado.
	 *
	 * @param object $entity current instance
	 * @param array $options Possible options are:
	 *     - `force`: set to true to delete record, anyway
	 * @return boolean true on success, false otherwise
	 */
	public function delete($entity, array $options = array()) {
		$options += array('force' => false);
		$deleted = $entity->schema('deleted');
		// TODO: use $deleted = $entity->hasField('deleted');
		if (is_null($deleted) || $options['force']) {
			unset($options['force']);
			$result = parent::delete($entity, $options);
			$versions = static::meta('versions');
			if (($versions === true) || (is_callable($versions) && $versions($entity, $options))) {
				if ($entity->version_id) {
					$key = Versions::key();
					$conditions = array($key => $entity->version_id);
					Versions::update(array('status' => 'deleted'), $conditions);
				}
			}
			return $result;
		}
		$entity->deleted = time();
		return $entity->save();
	}

	/**
	 * automatically adds timestamps on saving.
	 *
	 * In case of creation it correctly fills the `created` field with a unix timestamp.
	 * Same holds true for `updated` on updates, accordingly.
	 *
	 * @see lithium\data\Model
	 * @param object $entity current instance
	 * @param array $data Any data that should be assigned to the record before it is saved.
	 * @param array $options additional options
	 * @return boolean true on success, false otherwise
	 * @filter
	 */
	public function save($entity, $data = array(), array $options = array()) {
		if (!empty($data)) {
			$entity->set($data);
		}
		$schema = $entity->schema();
		foreach ($schema->fields() as $name => $meta) {
			if (isset($meta['type']) && $meta['type'] !== 'list') {
				continue;
			}
			if(is_string($entity->$name)) {
				$listData = explode("\n", $entity->$name);
				array_walk($listData, function (&$val) { $val = trim($val); });
				$entity->$name = $listData;
			}
		}
		$versions = static::meta('versions');
		if (!isset($options['callbacks']) || $options['callbacks'] !== false) {
			$field = ($entity->exists()) ? 'updated' : 'created';
			$entity->set(array($field => time()));
			if (($versions === true) || (is_callable($versions) && $versions($entity, $options))) {
				$version_id = Versions::add($entity, $options);
				if ($version_id) {
					$entity->set(compact('version_id'));
				}
			}
		}
		$result = parent::save($entity, null, $options);
		if ($result && isset($field) && $field == 'created') {
			if (($versions === true) || (is_callable($versions) && $versions($entity, $options))) {
				$version_id = Versions::add($entity, array('force' => true));
				if ($version_id) {
					$entity->set(compact('version_id'));
					return $entity->save(null, array('callbacks' => false));
				}
			}
		}
		return $result;
	}

	/**
	 * returns primary id as string from current entity
	 *
	 * @param object $entity instance of current Record
	 * @return string primary id of current record
	 */
	public function id($entity) {
		return (string) $entity->{static::key()};
	}

	/**
	 * generic method to retrieve a list or an entry of an array of a static property or a
	 * configuration with given properties list
	 *
	 * This method is used to allow an easy addition of key/value pairs, mainly for usage
	 * in a dropdown for a specific model.
	 *
	 * If you want to provide a list of available options, declare your properties in the same
	 * manner as `$_types` or `$_status` or create a new configuration with a slug that follows
	 * this structure: `{static::meta('sources')}.$property` (e.g. `content.types`).
	 * This array is used, then.
	 *
	 * @see radium\models\BaseModel::types()
	 * @see radium\models\BaseModel::status()
	 * @param string $property name of property to look for.
	 *               automatically prepended by an underscore: `_`. Must be static and public
	 * @param string $type type to look for, optional
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function _group($property, $type = null) {
		$field = sprintf('_%s', $property);
		$slug = sprintf('%s.%s', static::meta('source'), $property);
		if (!empty($type)) {
			$var =& static::$$field;
			$default = (isset($var[$type])) ? $var[$type] : false;
		} else {
			$default = static::$$field;
		}
		return $default; //Configurations::get($slug, $default, array('field' => $type));
	}

	/**
	 * all types for current model
	 *
	 * @param string $type type to look for
	 * @return mixed all types with keys and their name, or value of `$type` if given
	 */
	public static function types($type = null) {
		return static::_group(__FUNCTION__, $type);
	}

	/**
	 * render layout for current model
	 *
	 * @param string $name ...
	 * @return mixed renderLayouts with keys and their name, or value of `$name` if given
	 */
	public static function renderLayout($name = null) {
		return static::_group(__FUNCTION__, $name);
	}

	/**
	 * all status for current model
	 *
	 * @param string $status status to look for
	 * @return mixed all status with keys and their name, or value of `$status` if given
	 */
	public static function status($status = null) {
		return static::_group(__FUNCTION__, $status);
	}

	/**
	 * all actions available for current model
	 *
	 * @see radium\extensions\helper\Scaffold::actions()
	 * @param string $type type to look for, i.e. `first` or `all`
	 * @return mixed all actions with their corresponding configuration, suitable for Scaffold->actions()
	 */
	public static function actions($type = null) {
		return static::_group(__FUNCTION__, $type);
	}

	/**
	 * finds and loads entities that match slug subpattern
	 *
	 * @see lithium\data\Model::find()
	 * @param string $slug short unique string to look for
	 * @param string $status status must have
	 * @param array $options additional options to be merged into find options
	 * @return object|boolean found results as collection or false, if none found
	 * @filter
	 */
	public static function search($slug, $status = 'active', array $options = array()) {
		$params = compact('slug', 'status', 'options');
		return static::_filter(get_called_class() .'::search', $params, function($self, $params) {
			extract($params);
			$options['conditions'] = array(
				'slug' => array('like' => "/$slug/i"),
				'status' => $status,
				'deleted' => null, // only not deleted
			);
			return $self::find('all', $options);
		});
	}

	/**
	 * allows for data-retrieval of entities via file-based access
	 *
	 * In case you want to provide default file-data without inserting them into the database, you
	 * would need to create files based on model-name in a path like that
	 * `{:library}/data/{:class}/{:id}.neon` or `{:library}/data/{:class}/{:slug}.neon`
	 *
	 * In that case, an entity requested by id or slug would be loaded from file instead. Please pay
	 * attention, though that not all options are implemented, such as extended conditions, order,
	 * limit or page. This is meant to enable basic loading of id- or slug-based entity lookups.
	 *
	 * @see radium\util\Neon::file()
	 * @see radium\util\File::contents()
	 * @param string $type The find type, which is looked up in `Model::$_finders`. By default it
	 *        accepts `all`, `first`, `list` and `count`,
	 * @param array $options Options for the query. By default, accepts:
	 *        - `conditions`: The conditional query elements, e.g.
	 *                 `'conditions' => array('published' => true)`
	 *        - `fields`: The fields that should be retrieved. When set to `null`, defaults to
	 *             all fields.
	 *        - `order`: The order in which the data will be returned, e.g. `'order' => 'ASC'`.
	 *        - `limit`: The maximum number of records to return.
	 *        - `page`: For pagination of data.
	 * @return mixed
	 */
	public static function find($type, array $options = array()) {
		$result = parent::find($type, $options);
        $neon = static::meta('neon');
        if ($neon && (!$result || (!@count($result)))) {
			return Neon::find(get_called_class(), $type, $options);
		}
		return $result;
	}

	/**
	 * finds and loads active entity for given id
	 *
	 * @param string $id id of entity to load
	 * @param string|array $status expected status of record, can be string or an array of strings
	 * @param array $options additional Options to be used for the query
	 *        - `key`: the field to use for lookup, if given `id` is not a valid mongo-id
	 *                   defaults to `slug`
	 * @return object|boolean entity if found and active, false otherwise
	 * @filter
	 */
	public static function load($id, $status = 'active', array $options = array()) {
		$params = compact('id', 'status', 'options');
		return static::_filter(get_called_class() .'::load', $params, function($self, $params) {
			extract($params);
			$defaults = array('key' => 'slug');
			$options += $defaults;

			$key = ((strlen($id) == 24) && (ctype_xdigit($id)))
				? $self::key()
				: $options['key'];

			$options['conditions'] = ($key == $options['key'])
				? array($key => $id, 'status' => $status, 'deleted' => null)
				: array($key => $id);

			$options['order'] = ($key == $options['key'])
				? array('updated' => 'DESC')
				: null;

			unset($options['key']);
			$result = $self::find('first', $options);
			if (!$result) {
				return false;
			}
			if (!in_array($result->status, (array) $status)) {
				return false;
			}
			if (!empty($result->deleted)) {
				return false;
			}
			return $result;
		});
	}

	/**
	 * Returns all schema-fields, without their types
	 *
	 * @return array
	 */
	public static function fields() {
		$schema = static::schema();
		return $schema->names();
	}

	/**
	 * mass-import datasets
	 *
	 * @param array $data data as array, keyed off by ids and value beeing an array with all values
	 * @param array $options additional options
	 *        - `dry`: make a dry-run of import
	 *        - `prune`: empty collection before import, defaults to false
	 *        - `overwrite`: overwrite existing records, defaults to true
	 *        - `validate`: validate data, before save, defaults to true
	 *        - `strict`: defines if only fields in schema will be imported, defaults to true
	 *        - `callbacks`: enable callbacks in save-method, defaults to false
	 * @return array
	 */
	public static function bulkImport($data, array $options = array()) {
		$defaults = array(
			'dry' => false,
			'prune' => false,
			'overwrite' => true,
			'validate' => true,
			'strict' => true,
			'callbacks' => false,
		);
		$options += $defaults;
		$result = array();

		if ($options['prune'] && !$options['dry']) {
			static::remove();
		}

		if (!$options['overwrite']) {
			$conditions = array('_id' => array_keys($data));
			$fields = '_id';
			$present = static::find('all', compact('conditions', 'fields'));

			if($present) {
				$data = array_diff_key($data, $present->data());
				$skipped = array_keys(array_intersect_key($data, $present->data()));
				$result += array_fill_keys($skipped, 'skipped');
			}
		}
		if ($options['overwrite'] && !$options['dry']) {
			static::remove(array('_id' => array_keys($data)));
		}

		$callbacks = $options['callbacks'];
		$whitelist = ($options['strict']) ? static::schema()->names() : null;
		foreach ($data as $key => $item) {
			$entity = static::create();
			$entity->set($item);
			if ($options['validate'] || $options['dry']) {
				$result[$key] = (!$entity->validates())
					? $entity->errors()
					: 'valid';
				if ($result[$key] !== 'valid' || $options['dry']) {
					continue;
				}
			}
			if (!$options['dry']) {
				if ($options['overwrite']) {
					static::remove(array('_id' => $key));
				}
				$result[$key] = ($entity->save(null, compact('whitelist', 'callbacks')))
					? 'saved'
					: 'failed';
			}
		}
		return $result;
	}

	/**
	 * updates fields for multiple records, specified by key => value
	 *
	 * You can update the same field for more than on record with one call, like this:
	 *
	 * {{{
	 *   $data = array(
	 *     'id1' => 1,
	 *     'id2' => 2,
	 *   );
	 *   Model::multiUpdate('order', $data);
	 * }}}
	 *
	 * @param string $field name of field to update
	 * @param array $data array keys are primary keys, values will be set
	 * @param array $options Possible options are:
	 *     - `updated`: set to false to supress automatic updating of the `updated` field
	 * @return array an array containing all results
	 * @filter
	 */
	public static function multiUpdate($field, array $data, array $options = array()) {
		$defaults = array('updated' => true);
		$options += $defaults;
		$params = compact('field', 'data', 'options');
		return static::_filter(get_called_class() .'::multiUpdate', $params, function($self, $params) {
			extract($params);
			$key = static::key();
			$result = array();
			foreach ($data as $id => $value) {
				$update = array($field => $value);
				if ($options['updated']) {
					$update['updated'] = time();
				}
				$result[$id] = static::update($update, array($key => $id));
			}
			return $result;
		});
	}

	/**
	 * updates one or more fields per entity
	 *
	 * {{{$entity->updateFields(array('fieldname' => $value));}}}
	 *
	 * @see lithium\data\Model::update()
	 * @param object $entity current instance
	 * @param array $values an array of values to be changed
	 * @param array $options Possible options are:
	 *     - `updated`: set to false to supress automatic updating of the `updated` field
	 * @return true on success, false otherwise
	 * @filter
	 */
	public function updateFields($entity, array $values, array $options = array()) {
		$defaults = array('updated' => true);
		$options += $defaults;
		$params = compact('entity', 'values', 'options');
		return $this->_filter(get_called_class() .'::updateFields', $params, function($self, $params) {
			extract($params);
			$key = $self::key();
			$conditions = array($key => $entity->id());
			if ($options['updated']) {
				$values['updated'] = time();
			}
			$success = $self::update($values, $conditions);
			if (!$success) {
				$model = $entity->model();
				$msg = sprintf('Update of %s [%s] returned false', $model, $entity->id());
				$data = compact('values', 'conditions', 'model');
				return false;
			}
			$entity->set($values);
			return true;
		});
	}

	/**
	 * returns if current record is marked as deleted
	 *
	 * @param object $entity current instance
	 * @return boolean true if record is deleted, false otherwise
	 */
	public function deleted($entity) {
		return (bool) is_null($entity->deleted);
	}

	/**
	 * undeletes a record, in case it was marked as deleted
	 *
	 * @param object $entity current instance
	 * @return boolean true on success, false otherwise
	 */
	public function undelete($entity) {
		unset($entity->deleted);
		return is_null($entity->deleted) && $entity->save();
	}

	/**
	 * fetches the associated configuration record
	 *
	 * If current record has a configuration id set, it will load the corresponding record,
	 * but if it is not set, it will try to load a configuration by slug, with the following
	 * format: `<modelname>.<slug>`.
	 *
	 * @param object $entity current instance
	 * @param string $field what field (in case of array) to return
	 * @param array $options an array of options currently supported are
	 *              - `raw`     : returns Configuration object directly
	 *              - `default` : what to return, if nothing is found
	 *              - `flat`    : to flatten the result, if object/array-ish, defaults to false
	 * @return mixed configuration value
	 */
	public function configuration($entity, $field = null, array $options = array()) {
		$defaults = array('raw' => false);
		$options += $defaults;
		$load = (empty($entity->config_id))
			? sprintf('%s.%s', strtolower(static::meta('name')), $entity->slug)
			: $entity->config_id;

		$config = false; //$config = Configurations::load($load);
		if (!$config) {
			return null;
		}
		return ($options['raw']) ? $config : $config->val($field, $options);
	}

	/**
	 * fetches associated records
	 *
	 * {{{
	 *   $post->resolve('user'); // returns user, as defined in $post->user_id
	 * }}}
	 *
	 * @param object $entity current instance
	 * @param string|array $fields name of model to load
	 * @param array $options an array of options currently supported are
	 *              - `resolver` : closure that takes $name as parameter and returns full qualified
	 *                 model name.
	 *              - `slug` : true or false. If set to true, model is resolving by slug, not by ID.
	 *                 The slug has to be saved in a document schema key, named by the singular
	 *                 version of the model to reslove.
	 * @return array foreign object data
	 */
	public function resolve($entity, $fields = null, array $options = array()) {
		$resolver = function($name) {
			$modelname = Inflector::pluralize(Inflector::classify($name));
			return Libraries::locate('models', $modelname);
		};
		$slug = false;
		$defaults = compact('resolver', 'slug');
		$options += $defaults;

		switch (true) {
			case is_string($fields) && $options['slug']:
				$fields = array($fields);
				break;
			case is_array($fields) && $options['slug']:
				break;
			case is_string($fields):
				$fields = array((stristr($fields, '_id')) ? $fields : "{$fields}_id");
				break;
			case is_array($fields):
				$fields = array_map(function($field){
					return (stristr($field, '_id')) ? $field : "{$field}_id";
				}, $fields);
				break;
			case empty($fields):
				$fields = self::fields();
				break;
		}

		$result = array();
		foreach ($fields as $field) {
			if (!$options['slug']) {
				if (!preg_match('/^(.+)_id$/', $field, $matches)) {
					continue;
				}
				list($attribute, $name) = $matches;
			} else {
				$attribute = $field;
				$name = $field;
			}
			$model = $options['resolver']($name);
			if (empty($model)) {
				continue;
			}
			$foreign_id = (string) $entity->$attribute;
			if (!$foreign_id) {
				continue;
			}
			$result[$name] = $model::load($foreign_id);
		}
		return (count($fields) > 1) ? $result : array_shift($result);
	}

	/**
	 * returns a properly processed item as rss-item
	 *
	 * @param object $entity instance of current Record
	 * @param array $fields an array of additional fields to generate
	 * @param array $options an array of additional options
	 *              - `merge`: set to false, to process only given fields
	 * @return array an array containing relevant rss data as keys and their corresponding values
	 */
	public function rssItem($entity, $fields = array(), array $options = array()) {
		$defaults = array('merge' => true);
		$options += $defaults;
		static::$_rss['pubDate'] = function($object) {
			return date('D, d M Y g:i:s O', $object->created->sec);
		};
		$fields = ($options['merge']) ? array_merge(static::$_rss, $fields) : $fields;

		$item = array();
		foreach ($fields as $field => $source) {
			switch(true) {
				case is_callable($source):
					$item[$field] = $source($entity);
					break;
				case stristr($source, '{:'):
					$replace = array_merge(
						Environment::get('scaffold'),
						Set::flatten($entity->data()),
						array(
							'host' => $_SERVER['HTTP_HOST'],
						)
					);
					$item[$field] = StringDeprecated::insert($source, $replace);
					break;
				case isset($entity->$source):
					$item[$field] = $entity->$source;
					break;
			}
		}
		return $item;
	}

	/**
	 * return entity data, filtered by top-level keys
	 *
	 * return only subset of data, that is requested, as in $keys or as fallback taken from
	 * a static property of the corresponding model, named `$_publicFields`.
	 *
	 * @todo allow filtering with sub-keys, i.e. parent.sub
	 * @param object $entity instance of current Record
	 * @param string $key an array with all keys to be preserved, everything else is removed
	 * @return array only data, that is left after filtering everything, that is not in $keys
	 */
	public function publicData($entity, $keys = array()) {
		$keys = (empty($keys) && isset(static::$_publicFields))
			? static::$_publicFields
			: (array) $keys;
		$data = $entity->data();
		foreach ($data as $key => $item) {
			if (!in_array($key, $keys)) {
				unset($data[$key]);
			}
		}
		return $data;
	}

	/**
	 * counts distinct values regarding a specific field
	 *
	 * @param string $field name of the field to count distinct values against
	 * @param array $options an array of additional options
	 *              - `group`: set to $field, overwrite here
	 *              - `fields`: what fields to retrieve, useful if you overwrite the reduce code
	 *              - `initial`: initial object to aggregate data in, defaults to StdObject
	 *              - `reduce`: reduce method to be used within mongodb, must be of type `MongoCode`
	 * @return array an array containing relevant rss data as keys and their corresponding values
	 */
	public static function distinctCount($field = 'type', $options = array()) {
		$defaults = array(
			'group' => $field,
			'fields' => array('_id', $field),
			'initial' => new \stdClass,
			'reduce' => new \MongoCode(
				"function(doc, prev) { ".
					"if(typeof(prev[doc." . $field . "]) == 'undefined') {".
					"prev[doc." . $field . "] = 0;".
					"}".
					"prev[doc." . $field . "] += 1;".
				"}"
			),
		);
		$options += $defaults;

		$method = Inflector::pluralize($field);
		$result = (method_exists(__CLASS__, $method))
			? array_fill_keys(array_keys(static::$method()), 0)
			: array();

		$res = static::find('all', $options);
		if (!$res) {
			return $result;
		}

		$keys = $res->map(function($item) use ($field) {
			return $item->$field;
		});
		$values = $res->map(function($item) use ($field) {
			return $item->{$item->$field};
		});
		return array_merge($result, array_combine($keys->data(), $values->data()));
	}

	/**
	 * allows easy output of IniFormat into a property
	 *
	 * @param object $entity instance of current Record
	 * @param string $field name of property to retrieve data for
	 * @return array an empty array in case of errors or the saved data decoded
	 * @filter
	 */
	public function _ini($entity, $field) {
		$params = compact('entity', 'field');
		return $this->_filter(get_called_class() .'::_ini', $params, function($self, $params) {
			extract($params);
			if (empty($entity->$field)) {
				return array();
			}
			$data = IniFormat::parse($entity->$field);
			if (!is_array($data)) {
				return array();
			}
			return $data;
		});
	}

	/**
	 * Exports an array of custom finders which use the filter system to wrap around `find()`.
	 *
	 * @return void
	 */
	protected static function _findFilters() {
		$self = static::_object();
		$_query = $self->_query;

		$default = parent::_findFilters();
		$custom = array(
			'list' => function($self, $params, $chain) {
				$result = array();
				$meta = $self::meta();
				$name = $meta['key'];

				$options = &$params['options'];
				if (isset($options['field'])) {
					$options['fields'] = (array) $options['field'];
				}
				if ($options['fields'] === null || empty($options['fields'])) {
					list($name, $value) = array($self::meta('key'), null);
				} elseif (count($options['fields']) > 2) {
					list($name, $value) = array_slice($options['fields'], 0, 2);
				} elseif (count($options['fields']) > 1) {
					list($name, $value) = array_slice($options['fields'], 0, 2);
				} elseif (count($options['fields']) == 1) {
					$name = $meta['key'];
					$value = is_array($options['fields'])
						? $options['fields'][0]
						: $options['fields'];
				}
				foreach ($chain->next($self, $params, $chain) as $entity) {
					$key = $entity->{$name};
					$key = is_scalar($key) ? $key : (string) $key;
					$result[$key] = (is_null($value))
						? $entity->title()
						: $entity->{$value};
				}
				return $result;
			},
			'random' => function($self, $params, $chain){
				$amount = (int) $self::find('count', $params['options']);
				$offset = rand(0, $amount-1);
				$params['options']['offset'] = $offset;
				return $self::find('first', $params['options']);
			}
		);
		return array_merge($default, $custom);
	}

}

?>
