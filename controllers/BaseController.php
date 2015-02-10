<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use radium\extensions\storage\FlashMessage;

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

	protected function _message($message, array $options = array(), $key = 'flash_message') {
		return FlashMessage::write($message, $options, $key);
	}

	/**
	 * Generates conditions suitable for a search on mongodb based on form inputs according to schema
	 *
	 * @param string $defaults all default options you want to have set
	 * @return array merged array with all $defaults, $options and named params
	 */
	protected function _search($conditions) {
		$model = $this->scaffold['model'];

		$result = array('$or' => array());
		foreach($conditions as $field => $value) {
			if (empty($value) || $field == 'query') {
				continue;
			}
			$result[$field] = array_filter((array) $value);
		}
		$result = array_filter($result);
		if (!empty($conditions['query'])) {
			$like = array('like' => sprintf('/%s/i', $conditions['query']));
			$result['$or'][] = array('name' => $like);
			$result['$or'][] = array('slug' => $like);
			$result['$or'][] = array('notes' => $like);

			if(isset($model::$_searchable)){
				foreach($model::$_searchable AS $field){
					$result['$or'][] = array($field => $like);
				}
			}
		}

		return $result;
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
	 * Generates order out of named params
	 *
	 * @param string $defaults all default options you want to have set
	 * @return array merged array with all $defaults, $options and named params
	 */
	protected function _order($defaults = array()) {
		$order = array();
		if (!empty($this->request->query)) {
			$query = $this->request->query;
			if(isset($query['order'])){
				if (!stristr($query['order'], ',')) {
					$sorts[] = $query['order'];
				}else{
					$sorts = explode(',', $query['order']);
				}
				foreach($sorts AS $row){
					if (stristr($row, ':')) {
						list($key, $val) = explode(':', $row, 2);
						$order[$key] = $val;
					}
				}
				unset($this->request->query['order']);
			}
		}

		$order = array_merge($defaults, $order);
		return $order;
	}


	/**
	 * Generates offset out of params
	 *
	 * @param string $defaults all default options you want to have set
	 * @return array merged array with all $defaults, $options and named params
	 */
	protected function _offset($defaults = array()) {
		$itemsPerPage = $defaults['itemsPerPage'];
		$currentPage = $defaults['currentPage'];
		$allItems = $defaults['allItems'];

		$pages = ceil($allItems/$itemsPerPage);

		if($currentPage > $pages){
			$currentPage = $pages;
		}

		if($currentPage <= 0){
			$currentPage = 1;
		}

		$offset = ($currentPage-1)*$itemsPerPage;
		if($offset > $allItems){
			$offset = $allItems-$itemsPerPage;
		}

		$limit = $offset+$itemsPerPage;
		if($limit > $allItems){
			$limit = $allItems;
		}


		return array(
			'limit' => $limit,
			'offset' => $offset,
			'pages' => $pages,
			'page' => $currentPage,
			'itemsPerPage' => $itemsPerPage

		);
	}

	/**
	 * Get current page
	 *
	 * @param string $defaults all default options you want to have set
	 * @return integer of current page/default=0
	 */
	protected function _currentPage($defaults = array()) {
		$currentPage = 1;

		if(isset($this->request->query['p'])){
			$currentPage = (int) $this->request->query['p'];
		}

		if($currentPage < 1){
			$currentPage = 1;
		}
		unset($this->request->query['p']);

		return $currentPage;
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
			sscanf(str_replace('?', ' ', $this->request->env('REQUEST_URI')), '%s qqfile=%s', $t, $file);
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