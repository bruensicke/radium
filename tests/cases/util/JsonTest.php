<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\tests\cases\util;

use radium\util\Json;
use radium\extensions\errors\JsonException;

class JsonTest extends \lithium\test\Unit {

	public $_data = array(
		'name' => 'foo',
		'slug' => 'bar',
		'status' => 'active',
		'player' => array(
			'score' => '220',
			'rank' => '3',
		),
	);

	public function testNoError() {
		$expected = json_encode($this->_data);
		$this->assertEqual($this->_data, Json::decode($expected));
	}

	public function testDepthError() {
		$data = $this->_data + array(array(array('foo' => 'bar')));
		$expected = json_encode($data);
		$this->assertException('JSON Error: Maximum stack depth exceeded', function() use ($expected) {
			$result = Json::decode($expected, true, 2);
		});
	}

	public function testStateMismatchError() {
		$expected = json_encode($this->_data).'}';
		$this->assertException('JSON Error: State mismatch', function() use ($expected) {
			$result = Json::decode($expected);
		});
	}

	public function testMalformedJsonError() {
		$expected = json_encode($this->_data).'#';
		$this->assertException('JSON Error: Syntax error, malformed JSON', function() use ($expected) {
			$result = Json::decode($expected);
		});
	}


}

?>