<?php

namespace radium\tests\cases\models;

use radium\tests\mocks\MockContents as Contents;

use lithium\util\Set;

class BaseModelTest extends \lithium\test\Unit {

	public $_default = array(
		'name' => 'foo',
		'slug' => 'foo',
		'status' => 'active',
	);

	public function setUp() {

	}

	public function tearDown() {

	}

	public function testBooleanConfiguration() {
		$result = Contents::create(Set::merge($this->_default, array(
			'type' => 'page',
			'body' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
		)));
		$this->assertTrue($result->val());
	}


}

?>