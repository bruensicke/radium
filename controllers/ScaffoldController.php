<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use lithium\util\Inflector;

class ScaffoldController extends \radium\controllers\BaseController {

	public $model = null;

	public $scaffold = null;

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['paths']['template'][] = RADIUM_PATH . '/views/scaffold/{:template}.{:type}.php';
		$this->_scaffold();
		if ($this->request->is('ajax')) {
			return;
		}
		$this->set(array('scaffold' => $this->scaffold));
	}

	public function index() {
		$model = $this->scaffold['model'];
		$plural = $this->scaffold['plural'];
		$conditions = $this->_options();
		$result = $model::find('all', compact('conditions'));

		// $filters = $this->_filters();
		return array($plural => $result);
		// return array($plural => $result, 'filters' => $filters);
	}

	public function view($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];

		$result = $model::first($id);
		return array($singular => $result);
	}

	public function slug($slug) {
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];

		$result = $model::slug($slug);
		if (!$result) {
			$url = array('action' => 'add', 'args' => array("slug:$slug"));
			return $this->redirect($url);
		}
		$this->_render['template'] = 'view';
		return array($singular => $result);
	}

	public function add() {
		$model = $this->scaffold['model'];
		$singular = $this->scaffold['singular'];
		$object = $model::create($this->_options());

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		return array($singular => $object, 'errors' => $object->errors());
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
		return array($singular => $object, 'errors' => $object->errors());
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
		return array($singular => $object, 'errors' => $object->errors());
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

	protected function _filters() {
		$model = $this->_scaffold('model');
		return is_callable(array($model, 'filters')) ? $model::filters() : array();
	}

	/**
	 * Generates different variations of the configured $this->model property name
	 *
	 * @param string $type type defines, what variation of the default you want to have
	 *               available are 'class', 'model', 'singular', 'plural' and 'table' and 'human'.
	 *               if omitted, returns array containing all of them.
	 * @return array|string
	 **/
	protected function _scaffold($field = null) {
		if (is_null($this->scaffold)) {
			$class = basename(str_replace('\\', '/', $this->model));
			$this->scaffold = array(
				'controller' => $this->controller,
				'library' => $this->library,
				'class' => $class,
				'model' => $this->model,
				'singular' => Inflector::underscore(Inflector::singularize($class)),
				'plural' => strtolower(Inflector::pluralize($class)),
				'table' => Inflector::tableize($class),
				'human' => Inflector::humanize(Inflector::singularize($class)),
			);
		}
		if (!is_null($field)) {
			return (isset($this->scaffold[$field])) ? $this->scaffold[$field] : false;
		}
		return $this->scaffold;
	}

}

?>