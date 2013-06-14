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

	protected $_data = array();

	protected $_scaffold = array();

	/**
	 * initialize and check for li3_mustache library
	 *
	 */
	protected function _init() {
		parent::_init();
		$this->mustache = (bool) Libraries::get('li3_mustache');
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
	 * render scaffold-templates
	 *
	 * @param string $name name of template to render, possible options are:
	 *        `index`, `form`, `form.meta`, `form.config`, `errors`, `view`
	 * @param array $data additional data to be passed into template
	 * @param array $options additional options
	 *        - `mustache`: set to false to disable mustache-rendering, defaults to true
	 * @return string rendered html of template
	 */
	public function render($name, array $data = array(), array $options = array()) {
		$defaults = array('mustache' => true);
		$options += $defaults;
		$data = $this->_data($data);
		$scaffold = $data['scaffold'];

		if ($this->mustache && $options['mustache']) {
			switch ($name) {
				case 'index':
					$data['objects'] = (isset($data[$scaffold['plural']])
										&& is_callable(array($data[$scaffold['plural']], 'data')))
						? array_values($data[$scaffold['plural']]->data())
						: array();
				break;
				case 'form':
				case 'form.meta':
				case 'form.fields':
				case 'form.config':
					$options['mustache'] = false;
				break;
				case 'errors':
					$data['errors'] = (isset($data['errors']))
						? $this->data($data['errors'], array('flatten' => false))
						: array();
					if (empty($data['errors'])) {
						return;
					}
				break;
			}
		}
		try {
			$template = sprintf('%s/%s', $scaffold['plural'], $name);
			$method = ($this->mustache && $options['mustache']) ? '_mustache' : '_element';
			return $this->$method($template, $data, $options);
		} catch (TemplateException $e) {
			// $options['library'] = 'radium';
			if ($this->mustache && $options['mustache']) {
				if ($name === 'view') {
					$data['data'] = $this->data($data[$scaffold['singular']]->data());
				}
				try {
					return $this->_mustache($name, $data, $options);
				} catch (TemplateException $e) {

				}
			}
			$element = sprintf('scaffold/%s', $name);
			$options['library'] = 'radium';
			return $this->_element($element, $data, $options);
		}
	}

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
	 * allows merging of data from context with given data
	 *
	 * @param array $data additional data to be put into view
	 * @return array
	 */
	public function _data($data = array()) {
		if (!empty($data)) {
			return Set::merge($this->_context->data(), $data);
		}
		return $this->_context->data();
	}

	/**
	 * shortcut function to use mustache-helper to render mustache-based templates
	 *
	 * @param string $name which template to render
	 * @param array $data additional data to be put into mustache template
	 * @param array $options additional options to be put into mustache->render call
	 * @return string rendered html of mustache template
	 */
	public function _mustache($name, array $data = array(), array $options = array()) {
		return $this->_context->mustache->render($name, $data, $options);
	}

	/**
	 * shortcut function to render elements with current view context
	 *
	 * @param string $element name of element template to render
	 * @param array $data additional data to be put into view context
	 * @param array $options additional options to be put into view->render() call
	 * @return string rendered html of element template
	 */
	public function _element($element, array $data = array(), array $options = array()) {
		return $this->_context->view()->render(compact('element'), $data, $options);
	}
}
