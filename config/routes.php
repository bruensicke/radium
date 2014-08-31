<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

use lithium\net\http\Router;
use lithium\action\Response;
use lithium\core\Libraries;

use radium\models\Pages;

/**
 * In case you want to change the url-prefix to something different than `radium`
 * just specify the url_prefix like that:
 *
 * Libraries::add('radium', array('url_prefix' => 'admin'));
 */
$prefix = Libraries::get('radium', 'url_prefix') ? : 'radium';

Router::connect("/$prefix/api/{:args}", array('type' => 'json', 'library' => 'radium'), array('continue' => true));
Router::connect("/$prefix/{:action:(settings|export|schema)}/{:args}", array('library' => 'radium', 'controller' => 'radium'));
Router::connect("/$prefix/{:controller}/{:action}/{:id:[0-9a-f]{24}}/{:args}", array('library' => 'radium'));
Router::connect("/$prefix/{:controller}/{:action}/{:id:[0-9a-f]{24}}", array('library' => 'radium'));
Router::connect("/$prefix/{:controller}/{:action}/{:args}", array('library' => 'radium'));
Router::connect("/$prefix/{:controller}/{:action}", array('library' => 'radium'));
Router::connect("/$prefix/{:controller}", array('library' => 'radium'));
Router::connect("/$prefix", array('library' => 'radium', 'controller' => 'radium', 'action' => 'index'));

Router::connect('/page/{:args}', array('Page::view'), function($request){
	$fullslug = implode('/', $request->args);
	$page = Pages::load($fullslug, 'active', array('key' => 'fullslug'));
	if (!$page) {
		return false;
	}
	$request->page = $page;
	return $request;
});

/*
 we encourage you to add routes to your app-routes file, that look like this:
 */
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');

?>
