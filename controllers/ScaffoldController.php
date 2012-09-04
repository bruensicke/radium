<?php

namespace radium\controllers;

use lithium\util\Inflector;

class ScaffoldController extends \radium\controllers\BaseController {

	public $model = null;

	public function index() {
		$model = $this->_model();
		$plural = $this->_model('table');
		$result = $model::all();
		return array($plural => $result);
	}

	public function view() {
		$model = $this->_model();
		$singular = $this->_model('singular');

		$result = $model::first($this->request->id);
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
		$object->set($this->_options());

		if (!$object) {
			return $this->redirect(array('action' => 'index'));
		}
		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}
		return array($singular => $object);
	}

	public function duplicate() {
		$model = $this->_model();
		$singular = $this->_model('singular');
		$object = $model::first($this->request->id);

		$data = $object->data();
		unset($data[$model::key()]);
		$object = $model::create($data);
		$object->set($this->_options());

		if (!$object) {
			return $this->redirect(array('action' => 'index'));
		}

		if (($this->request->data) && $object->save($this->request->data)) {
			$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
			return $this->redirect($url);
		}

		$this->_render['template'] = 'edit';
		return array($singular => $object);
	}

	public function delete() {
		$model = $this->_model();
		$model::find($this->request->id)->delete();
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
			default:
				return $this->model;
		}
	}


}

?>