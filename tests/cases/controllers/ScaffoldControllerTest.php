<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\controllers;

use lithium\util\Inflector;
use lithium\tests\mocks\core\MockRequest;
use radium\tests\mocks\controllers\MockScaffoldController;

class ScaffoldControllerTest extends \lithium\test\Unit {

	protected $_controller;

	public function setUp() {
		$file = RADIUM_PATH . '/config/routes.php';
		call_user_func(function() use ($file) { include $file; });
		$request = new MockRequest;
		$request->library = 'radium';
		$request->controller = 'Configurations';
		$model = 'radium\tests\mocks\data\MockConfigurations';
		$this->_controller = new MockScaffoldController(compact('request', 'model'));
		// $this->_controller->model = $model;
		$this->_controller->_init();
	}

	public function tearDown() {}

	public function testIndexScaffold() {
		$this->_controller->index();
		$scaffold = $this->_controller->access('scaffold');
		$expected = array(
			'base' => '/radium/configurations',
			'controller' => 'Configurations',
			'library' => 'radium',
			'class' => 'MockConfigurations',
			'model' => 'radium\tests\mocks\data\MockConfigurations',
			'slug' => Inflector::underscore('MockConfigurations'),
			'singular' => Inflector::singularize('MockConfigurations'),
			'plural' => Inflector::pluralize('MockConfigurations'),
			'table' => Inflector::tableize('MockConfigurations'),
			'human' => Inflector::humanize('MockConfigurations'),
		);
		$this->assertEqual($expected, $scaffold);

	}

}