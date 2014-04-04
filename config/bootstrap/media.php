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
use Handlebars\Autoloader;


// Libraries::add('Handlebars', array(
//     // "prefix" => "Handlebars_",
//     // "includePath" => LITHIUM_LIBRARY_PATH, // or LITHIUM_APP_PATH . '/libraries'
//     // "bootstrap" => "Loader/Autoloader.php",
//     // "loader" => array("Handlebars", "register"),
//     // "transform" => function($class) { return str_replace("_", "/", $class) . ".php"; }
// ));

require RADIUM_PATH . '/libraries/Handlebars/Autoloader.php';
Autoloader::register();

/*
 * this filter allows automatic linking and loading of assets from `webroot` folder
 */
Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	list($library, $asset) = explode('/', ltrim($params['request']->url, '/'), 2) + array("", "");
	if ($asset && $library == 'radium' && ($path = Media::webroot($library)) && file_exists($file = "{$path}/{$asset}")) {
		return function() use ($file) {
			$info = pathinfo($file);
			$media = Media::type($info['extension']);
			$content = (array) $media['content'];

			return new Response(array(
				'headers' => array('Content-type' => reset($content)),
				'body' => file_get_contents($file)
			));
		};
	}
	return $chain->next($self, $params, $chain);
});

