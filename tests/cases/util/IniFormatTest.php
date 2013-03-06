<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\util;

use radium\util\IniFormat;
use lithium\util\Set;

class IniFormatTest extends \lithium\test\Unit {

	public $_data = array(
		'name' => 'foo',
		'slug' => 'bar',
		'status' => 'active',
		'player' => array(
			'score' => '220',
			'rank' => '3',
		),
	);

	public function testNoValue() {
		$data = "";
		$expected = array();
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = false;
		$expected = array();
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testSingleValue() {
		$data = "name=foo";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = foo";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = 'foo'";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = 'name = "foo"';
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name=foo\n";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\nname=foo\n";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\nname=foo\n\n";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\n\nname=foo\n\n";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testDoubleValues() {
		$data = "name=foo\nslug=bar";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = foo\nslug = bar";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = 'foo'\nslug = 'bar'";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = 'foo'\nslug = bar";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name = 'foo'\n\nslug = bar";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name=foo\nslug=bar\n";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\nname=foo\nslug=bar\n";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\nname=foo\n\nslug=bar\n";
		$expected = array('name' => 'foo', 'slug' => 'bar');
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testOneDimension() {
		$data = "name=foo\nslug=bar\nstatus=active";
		$expected = $this->_data;
		unset($expected['player']);
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\tname=foo\nslug=bar\nstatus=active";
		$expected = $this->_data;
		unset($expected['player']);
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "\n\n\tname=foo\n\nslug=bar\nstatus=active";
		$expected = $this->_data;
		unset($expected['player']);
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testTwoDimensions() {
		$data = "name=foo\nslug=bar\nstatus=active\nplayer.score=220\nplayer.rank=3";
		$expected = $this->_data;
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name=foo\nslug=bar\nstatus=active\n\nplayer.score=220\nplayer.rank=3";
		$expected = $this->_data;
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testDefaultValue() {
		$data = "";
		$expected = $default = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data, compact('default')));
	}

	public function testSections() {
		$data = "[foo]name=bar\nslug=baz";
		$expected = array('foo' => array('name' => 'bar', 'slug' => 'baz'));
		$this->assertEqual($expected, IniFormat::parse($data));
	}

	public function testWeirdValues() {
		$data = "name=foo\n//slug=bar";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
		$data = "name=foo\n//strange comment with (brackets)";
		$expected = array('name' => 'foo');
		$this->assertEqual($expected, IniFormat::parse($data));
	}

}

?>