<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

use radium\data\Converter;

define('RADIUM_PATH', dirname(__DIR__));

Converter::config(array(
	'plain' => array(
		'adapter' => 'Plain',
	),
	'html' => array(
		'adapter' => 'Html',
	),
	'mustache' => array(
		'adapter' => 'Mustache',
	),
	'markdown' => array(
		'adapter' => 'Markdown',
	),
));


?>