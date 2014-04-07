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

class RadiumController extends \radium\controllers\BaseController {

	public $layout = 'radium';

	public function _init() {
		parent::_init();
		$this->controller = $this->request->controller;
		$this->library = $this->request->library;

		$this->_render['paths']['template'][] = '{:library}/views/scaffold/{:template}.{:type}.php';
		$this->_render['paths']['template'][] = RADIUM_PATH . '/views/scaffold/{:template}.{:type}.php';
		$this->_render['layout'] = $this->layout;
	}

	public function index() {

	}

	public function export() {
		$models = Libraries::locate('models');
		return compact('models');
	}

	public function settings() {
		$settings = Configurations::settings();
		return compact('settings');
	}
}

?>
