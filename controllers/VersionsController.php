<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

class VersionsController extends \radium\controllers\ScaffoldController {

	public $model = 'radium\models\Versions';

	// @todo: add filter for additional id per model given
	public function available() {
		$for = implode('\\', func_get_args());
		$model = $this->scaffold['model'];
		$plural = $this->scaffold['plural'];
		$result = $model::available($for);
		$this->_render['template'] = 'index';
		return array($plural => $result, 'types' => $types);
	}

	public function restore($id = null) {
		$result = $this->_call(__FUNCTION__, $id);
		if (!$result) {
			$url = array('action' => 'index');
			return $this->redirect($url);
		}
		$url = array('action' => 'index');
		return $this->redirect($url);
	}

}

?>