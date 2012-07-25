<?php

namespace radium\tests\cases\models;

use radium\tests\mocks\data\MockConfigurations as Configurations;

use lithium\util\Set;

class ConfigurationsTest extends \lithium\test\Unit {

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
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => true,
		)));
		$this->assertTrue($result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => '1',
		)));
		$this->assertTrue($result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => 'helloworld',
		)));
		$this->assertTrue($result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => '0',
		)));
		$this->assertFalse($result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => '',
		)));
		$this->assertFalse($result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'boolean',
			'value' => false,
		)));
		$this->assertFalse($result->val());
	}

	public function testStringConfiguration() {
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'string',
			'value' => 'hello',
		)));
		$this->assertEqual('hello', $result->val());
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'string',
			'value' => '1',
		)));
		$this->assertEqual('1', $result->val());
	}

	public function testArrayConfiguration() {
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'array',
			'value' => 'foo=bar',
		)));
		$this->assertEqual(array('foo' => 'bar'), $result->val());
		$this->assertEqual('bar', $result->val('foo'));
		$result = Configurations::create(Set::merge($this->_default, array(
			'type' => 'array',
			'value' => "foo.bar=baz\nfoo.baz=bar",
		)));
		$this->assertEqual(array('foo' => array('bar' => 'baz', 'baz' => 'bar')), $result->val());
		$this->assertEqual(array('bar' => 'baz', 'baz' => 'bar'), $result->val('foo'));
		$this->assertEqual('baz', $result->val('foo.bar'));
	}

	public function testDropdownConfiguration() {
		$result = Configurations::dropdown();
		$expected = array(
			'active' => array(1 => 'first', 3 => 'third'),
			'inactive' => array(2 => 'second'),
		);
		$this->assertEqual($expected, $result);
	}

}

?>