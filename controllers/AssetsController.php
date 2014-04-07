<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use lithium\net\http\Router;

class AssetsController extends \radium\controllers\ScaffoldController {

	public $model = 'radium\models\Assets';

	public function show($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];

		$object = $model::first($id);
		if (!$object) {
			$url = array('action' => 'index');
			return $this->redirect($url);
		}
		return $object->render();
	}

	public function download($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];

		$object = $model::first($id);
		if (!$object) {
			$url = array('action' => 'index');
			return $this->redirect($url);
		}
		return $object->render(array(), array('download' => $object->filename));
	}

	public function run($id = null) {
		$id = (!is_null($id)) ? $id : $this->request->id;
		$model = $this->scaffold['model'];

		$object = $model::first($id);
		if (!$object) {
			$url = array('action' => 'index');
			return $this->redirect($url);
		}
		$result = $object->run();
		$url = array('action' => 'view', 'args' => array((string) $object->{$model::key()}));
		return $this->redirect($url);
	}

	public function upload() {
		if (!$this->request->is('ajax')) {
			return array();
		}
		$model = $this->model;
		$this->_render['type'] = 'json';
		$allowed = '*';
		$file = $this->_upload(compact('allowed'));
		if ($file['error'] !== UPLOAD_ERR_OK) {
			return $file;
		}
		$result = $model::init($file);
		if (!empty($result['asset'])) {
			$result['message'] = (!empty($result['success']))
				? 'upload successful'
				: 'file already present';
			$result['url'] = Router::match(
				array(
					'library' => 'radium',
					'controller' => 'assets',
					'action' => 'view',
					'id' => $result['asset']->id()),
				$this->request,
				array('absolute' => true)
			);
			// unset($result['asset']);
		}
		// if ($result['success']) {
		// 	unset($result['asset']);
		// }
		return $result;
	}

}

?>