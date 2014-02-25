<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use Handlebars\Handlebars as Engine;
use Handlebars\Helpers;
use Handlebars\Loader\FilesystemLoader;

use lithium\template\TemplateException;

// use lithium\util\Set;
// use lithium\core\Libraries;
// use lithium\core\Environment;

// use RuntimeException;

/**
 * Scaffold helper allows easy rendering of CRUD functionality
 *
 * use it in views, like this:
 *
 * echo $this->scaffold->render('index'); // lists a table of all objects
 * echo $this->scaffold->render('view'); // shows current object details
 * echo $this->scaffold->render('form'); // shows form for current object
 */
class Handlebars extends \lithium\template\Helper {

	/**
	 * holds instance of renderer
	 *
	 * @var object
	 */
	protected $_engine = array();

	/**
	 * Renders one mustache element with given $data
	 *
	 * @param string $name name of the element, below views/mustache
	 * @param string $data an array or object what to hand to the mustache layer
	 * @param string $options additional options, to put into the view()->render()
	 * @return string the rendered mustache template
	 */
	public function render($name, $data = array(), $options = array()) {
		$data += $this->_context->data();
		return $this->_engine->render($this->element($name, $data), $data);
	}

	/**
	 * shortcut function to render elements with current view context
	 *
	 * it looks by default into `views/elements/<plural_model>/<name>`, first at app-level
	 * and falling back to `views/elements/scaffold/<name>`, also first at app-level.
	 *
	 * if at app-level nothing is found, radium is used as fallback.
	 *
	 * @param string $element name of element template to render
	 * @param array $data additional data to be put into view context
	 * @param array $options additional options to be put into view->render() call
	 * @return string rendered html of element template
	 */
	public function element($element, array $data = array(), array $options = array()) {
		try {
			return $this->_context->view()->render(compact('element'), $data, $options);
		} catch (TemplateException $e) {
			return $this->_context->view()->render(compact('element'), $data, $options);
		}
		return '';
	}

	/**
	 * Adds helper to currrent instance of Handlebars Engine
	 *
	 * @param string $name name of helper to add helper for
	 * @param mixed $helper the content of the helper, may be an array or a closure
	 * @return void
	 */
	public function addHelper($name, $helper) {
		$this->_engine->addHelper($name, $helper);
	}

	/**
	 * initialize and check for scaffold data
	 *
	 */
	protected function _init() {
		parent::_init();
		$this->_engine = new Engine;
		$context = $this->_context;
		$this->addHelper('scaffold', function($a, $b, $c) use ($context) {
			if (isset($context->scaffold->$c)
			 && is_callable(array($context->scaffold->$c, '\lithium\data\Collection'))) {
				return $context->scaffold->$c->data();
			}
			if (isset($context->scaffold->$c)) {
				return $context->scaffold->$c;
			}
			return $context->scaffold->$c;
		});
	}
}