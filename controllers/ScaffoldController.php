<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use Exception;

use lithium\core\libraries;
use lithium\core\Environment;
use lithium\util\Inflector;
use lithium\net\http\Media;
use lithium\net\http\Router;
use lithium\analysis\Logger;

class ScaffoldController extends \radium\controllers\BaseController {

	public $model = null;

	public $scaffold = null;

	public $uploadPath = null;

	public $layout = 'radium';

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['layout'] = $this->request->is('ajax') ? false : $this->layout;
		$this->_scaffold();
	}

	public function index() {
		$model = $this->scaffold['model'];
		$conditions = $this->_options();
		$objects = $model::find('all', compact('conditions'));
		$types = is_callable(array($model, 'types')) ? $model::types() : array();
		return compact('objects', 'types');
	}

	public function view($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];

		$object = $model::first($id);
		if (!$object) {
			$url = array('action' => 'index');
			$message = 'Object not found';
			$this->_message($message);
			return $this->redirect($url);
		}
		return compact('object');
	}

	public function slug($slug) {
		$model = $this->scaffold['model'];

		$conditions = compact('slug');
		$object = $model::first(compact('conditions'));
		if (!$object) {
			$url = array('action' => 'add', 'args' => array("slug:$slug"));
			return $this->redirect($url);
		}
		$this->_render['template'] = 'view';
		return compact('object');
	}

	public function add() {
		$model = $this->scaffold['model'];
		$object = $model::create($this->_options());

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		$errors = $object->errors();
		return compact('object', 'errors');
	}

	public function edit($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];
		$object = $model::first($id);
		if (!$object) {
			return $this->redirect(array('action' => 'index'));
		}
		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		$object->set($this->_options());
		$errors = $object->errors();
		return compact('object', 'errors');
	}

	public function export($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$singular = strtolower($this->scaffold['singular']);
		$plural = strtolower($this->scaffold['table']);

		if (is_null($id)) {
			$limit = 0;
			$conditions = $this->_options();
			$result = $model::find('all', compact('limit', 'conditions'));
			$data = array($model => $result);
			$suffix = (!empty($conditions))
				? http_build_query($conditions, '', '-')
				: date('Y-m-d_H:i:s');
			$name = sprintf('%s-%s.json', $plural, $suffix);
		} else {
			$result = $model::first($id);
			$data = array($model => array($id => $result->data()));
			$name = sprintf('%s-%s.json', $singular, $id);
		}
		$this->response->headers('download', $name);
		$this->_render['hasRendered'] = true;
		return Media::render($this->response, $data, array('type' => 'json'));
	}

	public function duplicate($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];
		$object = $model::first($id);
		if (!$object) {
			return $this->redirect(array('action' => 'add'));
		}
		$data = $object->data();
		unset($data[$model::key()]);
		$object = $model::create($data);

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		$object->set($this->_options());
		$this->_render['template'] = 'edit';
		$errors = $object->errors();
		return compact('object', 'errors');
	}

	public function delete($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$model::find($id)->delete();
		return $this->redirect(array('action' => 'index'));
	}

	public function remove($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$conditions = array();
		if (!empty($id)) {
			$conditions[$model::key()] = $id;
		}
		$model::remove($conditions);
		return $this->redirect(array('action' => 'index'));
	}

	public function undelete($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$model::find($id)->undelete();
		return $this->redirect(array('action' => 'index'));
	}

	public function import() {
		if (!$this->request->is('ajax')) {
			return array();
		}
		$model = '\radium\models\Assets';
		$this->_render['type'] = 'json';
		$allowed = array('json');
		$file = $this->_upload(compact('allowed'));
		if ($file['error'] !== UPLOAD_ERR_OK) {
			return $file;
		}
		$data = $model::init($file, array('type' => 'import'));
		if (empty($data['asset'])) {
			$data['message'] = 'File could not be saved.';
			return $data;
		}
		if (!empty($data['asset']) && empty($data['success'])) {
			if (!empty($data['asset'])) {
				$data['message'] = $data['error'];
				$data['url'] = Router::match(
					array(
						'library' => 'radium',
						'controller' => 'assets',
						'action' => 'view',
						'id' => $data['asset']->id()),
					$this->request,
					array('absolute' => true)
				);
			}
			return $data;
		}
		$asset = $model::load($data['asset']->id());
		if (!$asset) {
			$data['message'] = 'File could not be loaded';
			return $data;
		}
		$result = $asset->import();
		$result = $result[$this->model];
		$valid = array_filter($result, function($ret){ return ($ret == 'saved') ? true : false; });
		$success = (bool) (count($valid) == count($result));
		$single = (bool) (count($result) == 1);
		$library = $this->library;
		$data['message'] = sprintf('Imported %s records from %s', count($valid), count($result));
		$url = array( // on one, go directly, else index
			'controller' => $this->controller,
			'action' => ($single) ? 'view' : 'index',
		);
		if (!empty($this->library)) {
			$url['library'] = $this->library;
		}
		if ($single) {
			$url['id'] = key($result);
		}
		$data['url'] = Router::match($url, $this->request, array('absolute' => true));
		return $data;
	}

	protected function _call($method, $id = null, $args = array()) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];

		$object = $model::first($id);
		if (!$object) {
			return false;
		}
		return call_user_func_array(array($object, $method), $args);
	}

	protected function _import($data) {
		$model = $this->scaffold['model'];
		$singular = strtolower($this->scaffold['singular']);
		$plural = $this->scaffold['table'];

		if (!is_array($data)) {
			return array('error' => 'could not read content.');
		}
		if (!isset($data[$singular]) && !isset($data[$plural])) {
			return array('error' => sprintf('file does not contain %s.', $plural));
		}
		if (isset($data[$singular])) {
			$object = $model::create($data[$singular]);
			$success = $object->save(null, array('callbacks' => false));
			if (!$success) {
				$errors = $object->errors();
				$error = 'validation errors.';
				return compact('error', 'errors');
			}
			$message = sprintf('%s "%s" imported.', $singular, $object->title());
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			$url = Router::match($url, $this->request);
			return compact('success', 'url', 'message');
		}
		if (isset($data[$plural])) {
			$errors = $valids = array();
			$data = $data[$plural];
			foreach ($data as $idx => $row) {
				$object = $model::create($row);
				$success = $object->save(null, array('callbacks' => false));
				if (!$success) {
					$errors[$idx] = $object->errors();
				} else {
					$valids[] = $idx;
				}
			}
			$success = (bool) (count($valids) == count($data));
			$message = ($success)
				? sprintf('%s %s imported', count($valids), $plural)
				: sprintf('%s from %s %s imported', count($valids), count($data), $plural);
			$url = Router::match(array('action' => 'index'), $this->request);
			return compact('success', 'url', 'message', 'errors');
		}
		return array('error' => 'content not valid.');
	}

	/**
	 * Generates different variations of the configured $this->model property name
	 *
	 * If no model is configured (i.e. `null`) - it automatically detects the corresponding
	 * model for this Controller via Inflection and `Libraries::locate()`.
	 *
	 * @see lithium\core\Libraries::locate()
	 * @param string $field defines, what variation of the default you want to have
	 *               available are 'class', 'model', 'singular', 'plural' and 'table' and 'human'.
	 *               if omitted, returns array containing all of them.
	 * @return array|string
	 **/
	protected function _scaffold($field = null) {

		if (is_null($this->model)) {
			$this->model = (string) Libraries::locate('models', $this->request->controller);
		}

		if (is_null($this->scaffold)) {
			$class = basename(str_replace('\\', '/', $this->model));
			$base = (!empty($this->library))
				? array('controller' => $this->controller, 'library' => $this->library)
				: array('controller' => $this->controller);
			$this->scaffold = array(
				'base' => Router::match($base, $this->request),
				'controller' => $this->controller,
				'library' => $this->library,
				'class' => $class,
				'model' => $this->model,
				'slug' => Inflector::underscore($class),
				'singular' => Inflector::singularize($class),
				'plural' => Inflector::pluralize($class),
				'table' => Inflector::tableize($class),
				'human' => Inflector::humanize($class),
			);
		}
		if (!is_null($field)) {
			return (isset($this->scaffold[$field])) ? $this->scaffold[$field] : false;
		}
		Environment::set(Environment::get(), array('scaffold' => $this->scaffold));
		return $this->scaffold;
	}

}

?>