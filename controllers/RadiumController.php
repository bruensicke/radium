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

	public function schema() {
		$data = Libraries::locate('models');
		$models = new Collection(compact('data'));
		$schema = $models->map(function($model) {
			return $model::schema();
		});
		// var_dump($schema);
		// var_dump($models);exit;
		return compact('models', 'schema');
	}
}

?>