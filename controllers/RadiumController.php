<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use radium\models\Configurations;
use lithium\core\Libraries;
use lithium\data\Connections;
use lithium\util\Set;
use lithium\util\Collection;

class RadiumController extends \radium\controllers\BaseController {

	public $layout = 'radium';

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['layout'] = $this->layout;
	}

	public function index() {

	}

	public function import() {
		Libraries::paths(array(
			'neons' => array('{:library}\data\{:class}\{:name}.neon'),
		));
		$libraries = Libraries::get(null, 'name');
		$data = array();
		$namespaces = true;
		foreach ($libraries as $library) {
			$files = Libraries::locate('neons', null, compact('namespaces', 'library'));
			if (!empty($files)) {
				$data[$library] = $files;
			}
		}
		return compact('data');
	}

	public function export() {
		$models = Libraries::locate('models');
		return compact('models');
	}

	public function settings() {
		$settings = Configurations::settings();
		return compact('settings');
	}

	public function connections() {
		$data = Connections::get();
		$connections = new Collection(compact('data'));
		if (true || $this->request->is('json')) {
			$connections->each(function($name) {
				$config = Connections::get($name, array('config' => true));
				unset($config['object']);
				return array_merge(compact('name'), Set::flatten($config));
			});
		}
		return compact('connections');
	}

	public function request() {
		$request = $this->request;
		return compact('request');
	}

	public function schema() {
		$data = Libraries::locate('models');
		$models = new Collection(compact('data'));
		if ($this->request->is('json')) {
			$models->each(function($model) {
				$schema = (is_callable(array($model, 'schema'))) ? $model::schema() : array(); 
				return array($model => ($schema) ? $schema->fields() : array());
			});
		}
		return compact('models');
	}
}

?>