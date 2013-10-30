<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\mocks\controllers;

class MockScaffoldController extends \radium\controllers\ScaffoldController {

	public $model = 'radium\tests\mocks\data\MockConfigurations';

	public function access($varName) {
		return $this->{$varName};
	}
}

?>