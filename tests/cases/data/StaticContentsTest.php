<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\data;

use radium\data\StaticContents;

class StaticContentsTest extends \lithium\test\Unit {

	public $_data = array(
		'simple' => array(
			'foo' => array(
				'name' => 'valid',
				'url' => 'present',
				'date' => 'irrelevant',
			),
			'bar' => array('one', 'two', 'three'),
			'baz' => 'This is an awesome string',
		),
		'subfolder' => array(
			'one' => array('megan' => array('joe', 'john')),
			'two' => 'just text',
			'three' => array('foo' => 'bar', 'baz' => 'bang'),
		),
		'formats' => array(
			'initest' => array('foo' => 'bar', 'baz' => 'bang'),
			'jsontest' => array('this' => 'here', 'another' => 4),
		),
	);

	public $options = array(
		'path' => null,
	);

	public function setUp() {
		$this->options['path'] = str_replace('cases/data', 'mocks/static', dirname(__FILE__));
	}

	public function testAvailable() {
		$expected = array('formats', 'simple', 'subfolder');
		$result = StaticContents::available($this->options);
		$this->assertEqual($expected, $result);
	}

	public function testSimple() {
		$input = 'simple';
		$expected = $this->_data['simple'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'simple.foo';
		$expected = $this->_data['simple']['foo'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'simple.bar';
		$expected = $this->_data['simple']['bar'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'simple.baz';
		$expected = $this->_data['simple']['baz'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);
	}

	public function testSubfolder() {
		$input = 'subfolder';
		$expected = $this->_data['subfolder'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'subfolder.one';
		$expected = $this->_data['subfolder']['one'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'subfolder.two';
		$expected = $this->_data['subfolder']['two'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);

		$input = 'subfolder.three';
		$expected = $this->_data['subfolder']['three'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);
	}

	public function testIniFormat() {
		$input = 'formats.initest.ini';
		$expected = $this->_data['formats']['initest'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);
	}

	public function testJsonFormat() {
		$input = 'formats.jsontest.json';
		$expected = $this->_data['formats']['jsontest'];
		$result = StaticContents::get($input, $this->options);
		$this->assertEqual($expected, $result);
	}

}

?>