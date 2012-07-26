<?php

namespace radium\controllers;

class PagesController extends \radium\controllers\BaseController {

	public $model = 'radium\models\Pages';

	public function file() {
		$path = func_get_args() ?: array('radium');
		return $this->render(array('template' => join('/', $path)));
	}
}

?>