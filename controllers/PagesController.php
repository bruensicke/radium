<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

class PagesController extends \radium\controllers\BaseController {

	public $model = 'radium\models\Contents';

	public function view() {
		$path = func_get_args() ?: array('radium');
		$model = $this->model;
		$content = $model::load(join('/', $path));
		if (!$content) {
			return $this->render(array('template' => join('/', $path)));
		}
		return compact('content');
	}
}

?>
