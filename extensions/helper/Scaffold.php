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
use lithium\core\Environment;
use lithium\template\TemplateException;

use RuntimeException;

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
		$data += $this->_scaffold;
		//$data = $this->_data($data, $options);
		return $this->element($name, $data, $options);
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
		$element = sprintf('%s/%s', $this->_scaffold['table'], $name);
		$hb = $this->_context->helper('handlebars');
		try {
			return $hb->render($element, $data, $options);
		} catch (TemplateException $e) {
			$element = sprintf('scaffold/%s', $name);
			return $hb->render($element, $data, $options);
		}
		return '';
	}

	/**
	 * renders a url, fitting for current scaffold-context
	 *
	 * @param string $action name of action to call
	 * @param array $args additional arguments for that action call
	 * @return array an array containing all relevant information in an array to build a url
	 */
	public function url($action = null, array $args = array()) {
		return $this->_context->url($this->action($action));
	}

	/**
	 * renders an array used for actions with the correct url
	 *
	 * @param string $action name of action to call
	 * @param array $args additional arguments for that action call
	 * @return array an array containing all relevant information in an array to build a url
	 */
	public function action($action = 'view', array $args = array()) {
		if (isset($this->_scaffold['object']) && is_a($this->_scaffold['object'], 'lithium\data\Entity')) {
			$args += array('id' => $this->_scaffold['object']->id());
		}
		$controller = (!empty($this->_scaffold['controller']))
			? $this->_scaffold['controller']
			: null;
		if (empty($this->_scaffold['library'])) {
			return compact('controller', 'action', 'args');
		}
		$library = (!empty($this->_scaffold['library']))
			? $this->_scaffold['library']
			: null;
		return compact('library', 'controller', 'action', 'args');
	}

	/**
	 * returns all scaffolded data, taken from Environment
	 *
	 * @return array an array containing all scaffold data
	 */
	public function data() {
		return $this->_scaffold;
	}

	/**
	 * getting/setting actions for current view context
	 *
	 * @param array $actions additional actions to be put into view
	 * @param array $options additional options
	 *        - `merge`: set to false to disable merging view data with context
	 * @return array
	 */
	public function actions($actions = null, array $options = array()) {
		$defaults = array('merge' => true);
		$options += $defaults;
		if (!$actions) {
			return $this->_scaffold['actions'];
		}
		if ($options['merge'] === false) {
			return $this->_scaffold['actions'] = $actions;
		}
		return $this->_scaffold['actions'] = array_merge($actions, $this->_scaffold['actions']);
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
		$params += array(array(), array());
		return $this->render($method, $params[0], $params[1]);
	}

	/**
	 * initialize and check for scaffold data
	 *
	 */
	protected function _init() {
		parent::_init();
		$this->_scaffold = Environment::get('scaffold');
		$this->_data = $this->_context->data();
		$actions = call_user_func(array($this->_scaffold['model'], 'actions'));
		if (isset($this->_data['object'])) {
			$this->_scaffold['object'] = $this->_data['object'];
			$this->_scaffold['actions'] = $actions['first'];
		}
		if (isset($this->_data['objects'])) {
			$this->_scaffold['objects'] = $this->_data['objects'];
			$this->_scaffold['actions'] = $actions['all'];
		}
	}
}
