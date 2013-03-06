<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\util;

use radium\util\Conditions;
use lithium\util\Set;

class ConditionsTest extends \lithium\test\Unit {

	public $_data = array(
		'name' => 'foo',
		'slug' => 'foo',
		'status' => 'active',
		'player' => array(
			'score' => '220',
			'rank' => '3',
		),
	);


	public function testLowerThanParse() {
		$conditions = '{:player.score} < 219';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} < 220';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} < 221';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} <= 219';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} <= 220';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} <= 221';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} < 3';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} <= 3';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} < 2';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:player.rank}" < 2 && "{:player.score}" < 219';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
	}

	public function testGreaterThanParse() {
		$conditions = '{:player.score} > 219';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} > 220';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} > 221';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} >= 219';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} >= 220';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} >= 221';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} > 3';
		$this->assertFalse(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} >= 3';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.rank} > 2';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:player.rank}" > 2 && "{:player.score}" > 219';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
	}

	public function testEqualParse() {
		$conditions = '"{:name}" == "foo"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:name}" != "bar"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:slug}" == "foo"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:status}" == "active"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '{:player.score} == 220';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:player.score}" == "220"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:player.rank}" == "3"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:player.rank}" != "foo"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
		$conditions = '"{:name}" == "foo" && "{:slug}" == "foo"';
		$this->assertTrue(Conditions::parse($conditions, $this->_data));
	}

	public function testEqualCheck() {
		$this->assertTrue(Conditions::check(4, '==', 4));
		$this->assertTrue(Conditions::check(4, '==', '4'));
		$this->assertTrue(Conditions::check('4', '==', 4));
		$this->assertTrue(Conditions::check('4', '==', '4'));
	}

	public function testGreaterCheck() {
		$this->assertTrue(Conditions::check(4, '>=', 4));
		$this->assertTrue(Conditions::check(4, '>', 3));
		$this->assertFalse(Conditions::check(4, '>', 4));
		$this->assertFalse(Conditions::check(4, '>', 5));
	}

	public function testLowerCheck() {
		$this->assertTrue(Conditions::check(4, '<=', 4));
		$this->assertFalse(Conditions::check(4, '<', '4'));
		$this->assertTrue(Conditions::check(4, '<', '5'));
	}

	public function testEqualCompare() {
		$this->assertTrue(Conditions::compare(4, '==', 4));
		$this->assertTrue(Conditions::compare(4, '==', '4'));
		$this->assertTrue(Conditions::compare('4', '==', 4));
		$this->assertTrue(Conditions::compare('4', '==', '4'));
		$this->assertFalse(Conditions::compare(4, '==', 3));
		$this->assertFalse(Conditions::compare(4, '==', 5));
		$this->assertFalse(Conditions::compare(5, '==', 4));
		$this->assertTrue(Conditions::compare(5, '==', 5));
		$this->assertFalse(Conditions::compare(5, '==', 6));
	}

	public function testNotEqualCompare() {
		$this->assertFalse(Conditions::compare(4, '!=', 4));
		$this->assertFalse(Conditions::compare(4, '!=', '4'));
		$this->assertFalse(Conditions::compare('4', '!=', 4));
		$this->assertFalse(Conditions::compare('4', '!=', '4'));
		$this->assertTrue(Conditions::compare(4, '!=', 3));
		$this->assertTrue(Conditions::compare(4, '!=', 5));
	}

	public function testGreaterCompare() {
		$this->assertTrue(Conditions::compare(4, '>=', 4));
		$this->assertTrue(Conditions::compare(4, '>', 3));
		$this->assertFalse(Conditions::compare(4, '>', 4));
		$this->assertFalse(Conditions::compare(4, '>', 5));
	}

	public function testLowerCompare() {
		$this->assertTrue(Conditions::compare(4, '<=', 4));
		$this->assertFalse(Conditions::compare(4, '<', '4'));
		$this->assertTrue(Conditions::compare('4', '<', '5'));
		$this->assertFalse(Conditions::compare('4', '>', '5'));
	}


}

?>