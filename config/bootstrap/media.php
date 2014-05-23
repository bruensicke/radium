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


// use Handlebars\Autoloader;

/*
 * We connect new ways to identify layouts, templates and elements to allow for easier
 * scaffolding and controller-wise separation.
 */
// Media::type('default', null, array(
// 	// 'view' => 'radium\template\View',
// 	// 'loader' => 'radium\template\Loader',
// 	'paths' => array(
// 		'layout' => array(
// 			'{:library}/views/layouts/{:controller}/{:layout}.{:type}.php',
// 			// '{:library}/views/layouts/scaffold/{:layout}.{:type}.php',
// 			'{:library}/views/layouts/{:controller}.{:type}.php',
// 			'{:library}/views/layouts/{:layout}.{:type}.php',
// 		),
// 		'template' => array(
// 			'{:library}/views/{:controller}/{:template}.{:type}.php',
// 			'{:library}/views/scaffold/{:template}.{:type}.php',
// 		),
// 		'element'  => array(
// 			'{:library}/views/elements/{:controller}/{:template}.{:type}.php',
// 			'{:library}/views/elements/{:template}.{:type}.php'
// 		)
// 	)
// ));

// Libraries::add('Handlebars', array(
//     "prefix" => "Handlebars_",
//     "includePath" => LITHIUM_LIBRARY_PATH, // or LITHIUM_APP_PATH . '/libraries'
//     "bootstrap" => "Loader/Autoloader.php",
//     "loader" => array("Handlebars", "register"),
//     "transform" => function($class) { return str_replace("_", "/", $class) . ".php"; }
// ));

// require RADIUM_PATH . '/libraries/Handlebars/Autoloader.php';
// Autoloader::register();

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

