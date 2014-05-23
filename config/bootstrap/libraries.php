<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

use lithium\action\Dispatcher;
use lithium\action\Response;
use lithium\net\http\Media;

use lithium\core\Libraries;


// Libraries::add('Handlebars', array(
//     "prefix" => "Handlebars",
//     // "includePath" => LITHIUM_LIBRARY_PATH, // or LITHIUM_APP_PATH . '/libraries'
//     "includePath" => RADIUM_PATH . '/libraries/Handlebars/src',
//     "bootstrap" => "Autoloader.php",
//     "loader" => array("Handlebars", "register"),
//     // "transform" => function($class) { return str_replace("_", "/", $class) . ".php"; }
// ));

require RADIUM_PATH . '/libraries/Handlebars/src/Handlebars/Autoloader.php';
Handlebars\Autoloader::register();
