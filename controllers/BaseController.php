<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use lithium\core\Libraries;
use lithium\analysis\Logger;

class BaseController extends \lithium\action\Controller {

	/**
	 * fully namespaced name of Model class to scaffold for
	 *
	 * @var string
	 */
	public $model = null;

	/**
	 * adds additional view template folders
	 */
	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;
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
		if (is_array($url) && !empty($this->library) && empty($url['library'])) {
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

	/**
	 * allows ajaxified upload of files
	 *
	 * @see
	 * @filter
	 * @param array $options [description]
	 * @return [type] [description]
	 */
	protected function _upload(array $options = array()) {
		$defaults = array(
			'allowed' => '*',
			'path' => Libraries::get(true, 'resources') . '/tmp/cache',
			'prefix' => __FUNCTION__,
			'chmod' => 0644,
		);
		$options += $defaults;
		if (!$this->request->is('ajax')) {
			return array('error' => 'only ajax upload allowed.');
		}
		if (empty($_GET['qqfile'])) {
			sscanf(str_replace('?', ' ', $this->request->query['url']), '%s qqfile=%s', $t, $file);
		} else {
			$file = $_GET['qqfile'];
		}
		$pathinfo = pathinfo($file);
		$name = $pathinfo['filename'];
		$type = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		if (!in_array($type, (array) $options['allowed']) && $options['allowed'] != '*') {
			$error = 'file-extension not allowed.';
			return compact('error', 'name', 'type');
		}
		$tmp_name = tempnam($options['path'], $options['prefix']);
		$input = fopen('php://input', 'r');
		$temp = fopen($tmp_name, 'w');
		$size = stream_copy_to_stream($input, $temp);
		@chmod($tmp_name, $options['chmod']);
		fclose($input);
		$msg = sprintf('upload of file %s.%s to %s', $name, $type, $tmp_name);
		$complete = (bool) ($size == (int) $_SERVER['CONTENT_LENGTH']);
		if (!$complete) {
			$msg = $error = $msg . ' failed.';
		} else {
			$msg = 'succesful ' . $msg;
			$error = UPLOAD_ERR_OK;
		}
		$data = compact('error', 'name', 'type', 'size', 'tmp_name');
		$priority = ($complete) ? 'debug' : 'warning';
		Logger::write($priority, $msg);
		return $data;
	}
}

?>