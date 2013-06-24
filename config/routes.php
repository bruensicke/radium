<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

use lithium\net\http\Router;
use lithium\action\Response;

Router::connect('/radium/{:path:js|css|font}/{:file}.{:type}', array(), function($request) {
	$req = $request->params;
	$file = dirname(__DIR__) . "/webroot/{$req['path']}/{$req['file']}.{$req['type']}";

	if (!file_exists($file)) {
		return false;
	}

	return new Response(array(
		'body' => file_get_contents($file),
		'headers' => array('Content-type' => str_replace(
			array('css', 'js', 'font'), array('text/css', 'text/javascript', "application/x-font-{$req['type']}"), $req['type']
		))
	));
});

Router::connect('/radium/api/{:args}', array('type' => 'json', 'library' => 'radium'), array('continue' => true));
Router::connect('/radium/{:controller}/{:action}/{:id:[0-9a-f]{24}}/{:args}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}/{:id:[0-9a-f]{24}}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}/{:args}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}', array('library' => 'radium'));
Router::connect('/radium/{:controller}', array('library' => 'radium'));
Router::connect('/radium', array('library' => 'radium', 'controller' => 'pages', 'action' => 'file'));

/*
 we encourage you to add routes to your app-routes file, that look like this:
 */
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');

?>