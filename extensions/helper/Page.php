<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use lithium\template\TemplateException;

class Page extends \lithium\template\Helper {

	/**
	 * holds instance of current page entity
	 *
	 * @var object
	 */
	protected $_page = null;

	/**
	 * Imports page from request object, if available
	 *
	 * @return void
	 */
	protected function _init() {
		parent::_init();
		$this->_page = ($this->_context->request()->page) ? : null;
	}

	/**
	 * returns widgets from current page
	 *
	 * @see radium\models\Pages::widgets()
	 * @return array array structure from widgets as from page->widgets()
	 */
	public function widgets() {
		if (is_null($this->_page)) {
			throw new TemplateException('No Page available');
		}
		return $this->_page->widgets();
	}

	/**
	 * returns current page entity
	 *
	 * @param string $name Name of attribute to return from page
	 * @return object instance of current page entity
	 */
	public function get($name = null) {
		if (!is_null($name)) {
			return $this->_page->$name ? : null;
		}
		return $this->_page;
	}

    public function is() {
        return (bool) $this->_page;
    }

	/**
	 * magic method to access page properties in view
	 *
	 * @param string $name Property name
	 * @return mixed value of given property
	 */
	public function __get($name) {
		if (isset($this->_page[$name])) {
			return $this->_page[$name];
		}
		return null;
	}

}