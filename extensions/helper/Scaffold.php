<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use lithium\util\Set;
use lithium\core\Libraries;
use lithium\template\TemplateException;

/**
 * Scaffold helper allows easy rendering of CRUD functionality
 *
 * use it in views, like this:
 *
 * echo $this->scaffold->render('index'); // lists a table of all objects
 * echo $this->scaffold->render('view'); // shows current object details
 * echo $this->scaffold->render('form'); // shows form for current object
 */
class Scaffold extends \lithium\template\Helper {

	/**
	 * holds a copy of all view-data from context
	 *
	 * @var array
	 */
	protected $_data = array();

	/**
	 * holds all scaffold-relevant data
	 *
	 * @var array
	 */
	protected $_scaffold = array();

	/**
	 * initialize and check for scaffold data
	 *
	 */
	protected function _init() {
		parent::_init();
		$this->_data = $this->_context->data();
		if (isset($this->_data['scaffold'])) {
			$this->_scaffold = $this->_data['scaffold'];
		}
		if (isset($this->_data[$this->_scaffold['singular']])) {
			$this->_scaffold['object'] = $this->_data[$this->_scaffold['singular']];
		}
		if (isset($this->_data[$this->_scaffold['plural']])) {
			$this->_scaffold['objects'] = $this->_data[$this->_scaffold['plural']];
		}
	}

	/**
	 * magic method to access scaffold properties in view
	 *
	 * @param string $name Property name.
	 * @return mixed Result.
	 */
	public function __get($name) {
		if (isset($this->_scaffold[$name])) {
			return $this->_scaffold[$name];
		}
		return null;
	}

	/**
	 * allows rendering of templates via their name as function
	 *
	 * {{{
	 *  // same as calling $this->scaffold->render('index');
	 *  echo $this->scaffold->index();
	 * }}}
	 *
	 * @see radium\extensions\helper\Scaffold::render()
	 * @param string $method what method was called
	 * @param array $params parameters that were given
	 * @return mixed outpuf of render-method
	 */
	public function __call($method, $params) {
		return $this->render($method, $params[0], $params[1]);
	}

	/**
	 * render scaffold-templates
	 *
	 * @param string $name name of template to render, possible options are:
	 *        `index`, `form`, `form.meta`, `form.config`, `errors`, `view`
	 * @param array $data additional data to be passed into template
	 * @param array $options additional options
	 *        - `merge`: set to false to disable merging view data with context
	 * @return string rendered html of template
	 */
	public function render($name, array $data = array(), array $options = array()) {
		$data = $this->_data($data, $options);
		return $this->element($name, $data, $options);
	}

	/**
	 * renders an array used for actions with the correct url
	 *
	 * @param string $action name of action to call
	 * @param array $args additional arguments for that action call
	 * @return array an array containing all relevant information in an array to build a url
	 */
	public function action($action = 'view', array $args = array()) {
		if (isset($this->_scaffold['object'])) {
			$args += array('id' => $this->_scaffold['object']->id());
		}
		return compact('action', 'args');
	}

	/**
	 * Parses an associative array into an array, containing one
	 * array for each row, that has 'key' and 'value' filled
	 * as expected. That makes rendering of arbitrary meta-data
	 * much simpler, e.g. if you do not know, what data you are
	 * about to retrieve.
	 *
	 * @param array $data an associative array containing mixed data
	 * @return array an numerical indexed array with arrays for each
	 *         item in $data, having 'key' and 'value' set accordingly
	 */
	public function data(array $data = array(), array $options = array()) {
		$defaults = array('flatten' => true);
		$options += $defaults;
		if ($options['flatten']) {
			$data = Set::flatten($data);
		}
		return array_map(function($key, $value) {
			return compact('key', 'value');
		}, array_keys($data), $data);
	}

	/**
	 * allows merging of data from context with given data
	 *
	 * @param array $data additional data to be put into view
	 * @param array $options additional options
	 *        - `merge`: set to false to disable merging view data with context
	 * @return array
	 */
	public function _data($data = array(), array $options = array()) {
		$defaults = array('merge' => true);
		$options += $defaults;
		if ($options['merge'] === false) {
			return $data;
		}
		if (!empty($data)) {
			return Set::merge($this->_context->data(), $data);
		}
		return $this->_context->data();
	}

	/**
	 * shortcut function to use mustache-helper to render mustache-based templates
	 *
	 * it looks by default into `views/mustache/<plural_model>/<name>`, first at app-level
	 * and falling back to `views/mustache/scaffold/<name>`, also first at app-level.
	 *
	 * if at app-level nothing is found, radium is used as fallback.
	 *
	 * @param string $name which template to render
	 * @param array $data additional data to be put into mustache template
	 * @param array $options additional options to be put into mustache->render call
	 * @return string rendered html of mustache template
	 */
	public function mustache($name, array $data = array(), array $options = array()) {
		$element = sprintf('%s/%s', $this->_scaffold['plural'], $name);
		try {
			return $this->_context->mustache->render($element, $data, $options);
		} catch (TemplateException $e) {
			$element = sprintf('scaffold/%s', $name);
			return $this->_context->mustache->render($element, $data, $options);
		}
		return '';
	}

	/**
	 * shortcut function to render elements with current view context
	 *
	 * it looks by default into `views/elements/<plural_model>/<name>`, first at app-level
	 * and falling back to `views/elements/scaffold/<name>`, also first at app-level.
	 *
	 * if at app-level nothing is found, radium is used as fallback.
	 *
	 * @param string $name name of element template to render
	 * @param array $data additional data to be put into view context
	 * @param array $options additional options to be put into view->render() call
	 * @return string rendered html of element template
	 */
	public function element($name, array $data = array(), array $options = array()) {
		$element = sprintf('%s/%s', $this->_scaffold['plural'], $name);
		try {
			return $this->_context->view()->render(compact('element'), $data, $options);
		} catch (TemplateException $e) {
			$element = sprintf('scaffold/%s', $name);
			return $this->_context->view()->render(compact('element'), $data, $options);
		}
		return '';
	}
}
