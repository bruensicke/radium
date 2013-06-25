<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

define('RADIUM_PATH', dirname(__DIR__));

use lithium\action\Dispatcher;
use lithium\action\Response;
use lithium\net\http\Media;

use radium\data\Converter;

/*
 * this filter allows automatic linking and loading of assets from `webroot` folder
 */
Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	list($tmp, $library, $asset) = explode('/', $params['request']->url, 3) + array("", "", "");
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

Converter::config(array(
	'array' => array(
		'adapter' => 'Ini',
	),
	'ini' => array(
		'adapter' => 'Ini',
	),
	'neon' => array(
		'adapter' => 'Neon',
	),
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