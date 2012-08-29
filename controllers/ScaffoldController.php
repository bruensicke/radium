<?php

namespace radium\controllers;

use lithium\util\Inflector;

class ScaffoldController extends \radium\controllers\BaseController {

	public $model = null;

	public function index() {
		$model = $this->model();
		$plural = $this->model('table');
		$result = $model::all();
		return array($plural => $result);
	}

	public function view() {
		$model = $this->model();
		$singular = $this->model('singular');

		$result = $model::first($this->request->id);
		return array($singular => $result);
	}

	public function add() {
		$model = $this->model();
		$singular = $this->model('singular');
		$object = $model::create($this->_options());

		if (($this->request->data) && $object->save($this->request->data)) {
			return $this->redirect(array(
				'library' => $this->library, 'action' => 'view', 'args' => array($object->_id)
			));
		}
		return array($singular => $object);
	}

	public function edit() {
		$model = $this->model();
		$singular = $this->model('singular');
		$object = $model::first($this->request->id);
		$object->set($this->_options());

		if (!$object) {
			return $this->redirect(array('library' => $this->library, 'action' => 'index'));
		}
		if (($this->request->data) && $object->save($this->request->data)) {
			return $this->redirect(array(
				'library' => $this->library, 'action' => 'view', 'args' => array($object->_id)
			));
		}
		return array($singular => $object);
	}

	public function delete() {
		$model = $this->model();
		$model::find($this->request->id)->delete();
		return $this->redirect(array('library' => $this->library, 'action' => 'index'));
	}

	public function undelete() {
		$model = $this->model();
		$model::find($this->request->id)->undelete();
		return $this->redirect(array('library' => $this->library, 'action' => 'index'));
	}

	/**
	 * Generates different variations of the configured $this->model property name
	 *
	 * @param string $type type defines, what variation of the default you want to have
	 *               available are 'singular', 'plural' and 'table', if omitted, returns
	 *               the full qualified modelname, as defined in $this->model.
	 * @return string
	 **/
	protected function model($type = 'class') {
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