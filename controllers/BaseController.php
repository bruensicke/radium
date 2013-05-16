<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

class BaseController extends \lithium\action\Controller {

	public $model = null;

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['paths'] = array(
			'template' => array(
				LITHIUM_APP_PATH . '/views/{:controller}/{:template}.{:type}.php',
				RADIUM_PATH . '/views/{:controller}/{:template}.{:type}.php',
				'{:library}/views/{:controller}/{:template}.{:type}.php',
			),
			'layout' => array(
				LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.php',
				RADIUM_PATH . '/views/layouts/{:layout}.{:type}.php',
				'{:library}/views/layouts/{:layout}.{:type}.php',
			),
			'element' => array(
				LITHIUM_APP_PATH . '/views/elements/{:template}.{:type}.php',
				RADIUM_PATH . '/views/elements/{:template}.{:type}.php',
				'{:library}/views/elements/{:template}.{:type}.php',
			),
			'mustache' => array(
				LITHIUM_APP_PATH . '/views/mustache/{:template}.{:type}.php',
				RADIUM_PATH . '/views/mustache/{:template}.{:type}.php',
				'{:library}/views/mustache/{:template}.{:type}.php',
			),
		);
	}

	/**
	 * automatic supplement of library for redirects
	 *
	 * @see lithium\net\http\Router::match()
	 * @see lithium\action\Controller::$response
	 * @see lithium\action\Controller::redirect()
	 * @param mixed $url The location to redirect to, provided as a string relative to the root of
	 *              the application, a fully-qualified URL, or an array of routing parameters to be
	 *              resolved to a URL. Post-processed by `Router::match()`.
	 * @param array $options Options when performing the redirect. Available options include:
	 *              - `'status'` _integer_: The HTTP status code associated with the redirect.
	 *                Defaults to `302`.
	 *              - `'head'` _boolean_: Determines whether only headers are returned with the
	 *                response. Defaults to `true`, in which case only headers and no body are
	 *                returned. Set to `false` to render a body as well.
	 *              - `'exit'` _boolean_: Exit immediately after rendering. Defaults to `false`.
	 *                Because `redirect()` does not exit by default, you should always prefix calls
	 *                with a `return` statement, so that the action is always immediately exited.
	 * @return object Returns the instance of the `Response` object associated with this controller.
	 */
	public function redirect($url, array $options = array()) {
		return parent::redirect($this->_url($url), $options);
	}

	/**
	 * automatic supplement of library for redirects
	 *
	 * @param mixed $url The location to redirect to, provided as a string relative to the root of
	 *              the application, a fully-qualified URL, or an array of routing parameters to be
	 *              resolved to a URL. Post-processed by `Router::match()`.
	 * @param mixed $url The location including the library parameter
	 */
	protected function _url($url) {
		if (is_string($url)) {
			$url = array($url);
		}
		if (!empty($this->library) && empty($url['library'])) {
			$url['library'] = $this->library;
		}
		return $url;
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
					list($key, $val) = explode(':', $param, 2);
				} else {
					$key = $param;
					$val = true;
				}
				$options[$key] = (is_numeric($val)) ? (int)$val : $val;
			}
		}
		if (!empty($this->request->query)) {
			$options += $this->request->query;
			unset($options['url']);
		}
		$options = array_merge($defaults, $options);
		return $options;
	}
}

?>