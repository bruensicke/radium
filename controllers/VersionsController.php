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