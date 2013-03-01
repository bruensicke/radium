<?php

namespace radium\controllers;

use lithium\util\Inflector;

class ScaffoldController extends \radium\controllers\BaseController {

	public $model = null;

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['paths']['template'][] = RADIUM_PATH . '/views/scaffold/{:template}.{:type}.php';
	}

	public function index() {
		$model = $this->_model();
		$plural = $this->_model('table');
		$human = $this->_model('human');
		$conditions = $this->_options();
		$result = $model::find('all', compact('conditions'));
		$types = $model::types();
		if ($this->request->is('ajax')) {
			$conditions = $this->_options();
			$result = $model::find('all', compact('conditions'));
			return array($plural => $result, 'types' => $types);
		}
		return array($plural => $result, 'types' => $types, 'plural' => $plural, 'human' => $human);
	}

	public function view() {
		$model = $this->_model();
		$singular = $this->_model('singular');

		$result = $model::first($this->request->id);
		return array($singular => $result);
	}

	public function slug($slug) {
		$model = $this->_model();
		$singular = $this->_model('singular');

		$result = $model::slug($slug);
		if (!$result) {
			$url = array('action' => 'add', 'args' => array("slug:$slug"));
			return $this->redirect($url);
		}
		$this->_render['template'] = 'view';
		return array($singular => $result);
	}

	public function add() {
		$model = $this->_model();
		$singular = $this->_model('singular');
		$object = $model::create($this->_options());

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		return array($singular => $object, 'errors' => $object->errors());
	}

	public function edit() {
		$model = $this->_model();
		$singular = $this->_model('singular');
		$object = $model::first($this->request->id);
		if (!$object) {
			return $this->redirect(array('action' => 'index'));
		}
		$object->set($this->_options());
		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		return array($singular => $object, 'errors' => $object->errors());
	}

	public function duplicate() {
		$model = $this->_model();
		$singular = $this->_model('singular');
		$object = $model::first($this->request->id);
		if (!$object) {
			return $this->redirect(array('action' => 'add'));
		}
		$data = $object->data();
		unset($data[$model::key()]);
		$object = $model::create($data);
		$object->set($this->_options());

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}

		$this->_render['template'] = 'edit';
		return array($singular => $object, 'errors' => $object->errors());
	}

	public function delete() {
		$model = $this->_model();
		$model::find($this->request->id)->delete();
		return $this->redirect(array('action' => 'index'));
	}

	public function remove() {
		$model = $this->_model();
		$conditions = array();
		if (!empty($this->request->id)) {
			$conditions[$model::key()] = $this->request->id;
		}
		$model::remove($conditions);
		return $this->redirect(array('action' => 'index'));
	}

	public function undelete() {
		$model = $this->_model();
		$model::find($this->request->id)->undelete();
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * Generates different variations of the configured $this->model property name
	 *
	 * @param string $type type defines, what variation of the default you want to have
	 *               available are 'singular', 'plural' and 'table', if omitted, returns
	 *               the full qualified modelname, as defined in $this->model.
	 * @return string
	 **/
	protected function _model($type = 'class') {
		$class_name = basename(str_replace('\\', '/', $this->model));
		switch ($type) {
			case 'singular':
				return Inflector::underscore(Inflector::singularize($class_name));
			case 'plural':
				return Inflector::pluralize($class_name);
			case 'table':
				return Inflector::tableize($class_name);
			case 'human':
				return Inflector::humanize($class_name);
			default:
				return $this->model;
		}
	}


}

?>