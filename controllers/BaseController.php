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
}

?>