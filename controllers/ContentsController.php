<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

class ContentsController extends \radium\controllers\ScaffoldController {

	public $model = 'radium\models\Contents';

	public function _init() {
		parent::_init();
		$template = 'table';
		$name = 'contents';
		$this->set(compact('template', 'name'));
	}

	public function index($type = 'all') {
		$model = $this->scaffold['model'];
		$plural = $this->scaffold['plural'];
		$conditions = $this->_options();
		$result = $model::$type();
		return array($plural => $result);
	}
}

?>