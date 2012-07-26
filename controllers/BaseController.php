<?php

namespace radium\controllers;

use lithium\util\Inflector;

class BaseController extends \lithium\action\Controller {

	public $model = null;

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['paths'] = array(
			'template' => array(
				LITHIUM_APP_PATH . '/views/{:controller}/{:template}.{:type}.php',
				'{:library}/views/{:controller}/{:template}.{:type}.php',
			),
			'layout' => array(
				LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.php',
				'{:library}/views/layouts/{:layout}.{:type}.php',
			),
			'element' => array(
				LITHIUM_APP_PATH . '/views/elements/{:template}.{:type}.php',
				'{:library}/views/elements/{:template}.{:type}.php',
			),
			'mustache' => array(
				RADIUM_PATH . '/views/mustache/{:template}.{:type}.php',
			),
		);
	}

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
		$object = $model::create();

		if (($this->request->data) && $object->save($this->request->data)) {
			return $this->redirect(array("{$this->controller}::view", 'args' => array($object->_id)));
		}
		return array($singular => $object);
	}

	public function edit() {
		$model = $this->model();
		$singular = $this->model('singular');
		$object = $model::first($this->request->id);

		if (!$object) {
			return $this->redirect("{$this->controller}::index");
		}
		if (($this->request->data) && $object->save($this->request->data)) {
			return $this->redirect(array("{$this->controller}::view", 'args' => array($object->_id)));
		}
		return array($singular => $object);
	}

	public function delete() {
		$model = $this->model();
		$model::find($this->request->id)->delete();
		return $this->redirect("{$this->controller}::index");
	}

	public function undelete() {
		$model = $this->model();
		$model::find($this->request->id)->undelete();
		return $this->redirect("{$this->controller}::index");
	}

	/**
	 * Generates options out of named params
	 *
	 * @param string $defaults all default options you want to have set
	 * @return array merged array with all $defaults, $options and named params
	 */
	protected function _options($defaults = array()) {
		$options = array();
		if (!empty($this->request->args)) {
			foreach ($this->request->args as $param) {
				if (stristr($param, ':')) {
					list($key, $val) = explode(':', $param);
				} else {
					$key = $param;
					$val = true;
				}
				$options[$key] = (is_numeric($val)) ? (int)$val : $val;
			}
		}
		if (!empty($this->request->get)) {
			$options += $this->request->get;
		}
		$options = array_merge($defaults, $options);
		return $options;
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