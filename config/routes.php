<?php
use lithium\net\http\Router;

Router::connect('/radium/api/{:args}', array('type' => 'json', 'library' => 'radium'), array('continue' => true));
Router::connect('/radium/{:controller}/{:action}/{:id:[0-9a-f]{24}}/{:args}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}/{:id:[0-9a-f]{24}}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}/{:args}', array('library' => 'radium'));
Router::connect('/radium/{:controller}/{:action}', array('library' => 'radium'));
Router::connect('/radium/{:controller}', array('library' => 'radium'));
Router::connect('/radium', array('library' => 'radium', 'controller' => 'pages', 'action' => 'file'));

// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');

?>