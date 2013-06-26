<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\extensions\adapter\converters;

use radium\extensions\adapter\converters\Json;

class JsonTest extends \lithium\test\Unit {

	public $instance = null;

	public $_data = array(
		'name' => 'foo',
		'slug' => 'bar',
		'status' => 'active',
		'player' => array(
			'score' => '220',
			'rank' => '3',
		),
	);

	public function setUp() {
		$this->instance = new Json;
	}

	public function tearDown() {
		unset($this->instance);
	}

	public function testGetSimple() {
		$input = '';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = '{}';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = '{"foo": "bar"}';
		$expected = array('foo' => 'bar');
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = "{\n\"bar\": \"baz\"}";
		$expected = array('bar' => 'baz');
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = json_encode($this->_data);
		$expected = $this->_data;
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);
	}

	public function testGetValidOtherInput() {
		$input = 4;
		$expected = 4;
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

	}

	public function testGetDefaultValue() {
		$input = '{"foo": "bar"}';
		$default = 'fallback';
		$expected = $default;
		$result = $this->instance->get($input, 'notThere', compact('default'));
		$this->assertEqual($expected, $result);

		$input = '{"foo": "bar"}';
		$default = 'bar';
		$expected = $default;
		$result = $this->instance->get($input, 'foo', compact('default'));
		$this->assertEqual($expected, $result);

	}

	public function testGetInvalidInput() {
		$input = 'hello world!';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = '{foo: "bar"}';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = '{"foo": bar}';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);

		$input = '{"foo": bar';
		$expected = array();
		$result = $this->instance->get($input);
		$this->assertEqual($expected, $result);
	}



}

?>