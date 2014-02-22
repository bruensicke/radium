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
use Handlebars\Autoloader;


// Libraries::add('Handlebars', array(
//     // "prefix" => "Handlebars_",
//     // "includePath" => LITHIUM_LIBRARY_PATH, // or LITHIUM_APP_PATH . '/libraries'
//     // "bootstrap" => "Loader/Autoloader.php",
//     // "loader" => array("Handlebars", "register"),
//     // "transform" => function($class) { return str_replace("_", "/", $class) . ".php"; }
// ));

require dirname(__DIR__) . '/libraries/Handlebars/Autoloader.php';
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

use lithium\util\Inflector;
use lithium\util\Validator;

/*
 * We want to avoid method names like `statuses()` - therefore, we go this route
 */
Inflector::rules('uninflected', 'status');

/*
 * apply new validation rules to the Validator class, because we need them
 */
Validator::add(array(
	'slug' => '/^[a-z0-9\_\-\.]*$/',			// only lowercase, digits and dot
	'loose_slug' => '/^[a-zA-Z0-9\_\-\.]*$/',	// both cases, digits and dot
	'strict_slug' => '/^[a-z][a-z0-9\_\-]*$/',  // only lowercase, starting with letter, no dot
	'isUnique' => function ($value, $format, $options) {
		$conditions = array($options['field'] => $value);
		foreach ((array) $options['model']::meta('key') as $field) {
			if (!empty($options['values'][$field])) {
				$conditions[$field] = array('!=' => $options['values'][$field]);
			}
		}
		$fields = $options['field'];
		return is_null($options['model']::find('first', compact('fields', 'conditions')));
	},
	'status' => function ($value, $format, $options) {
		return (bool) $options['model']::status($value);
	},
	'type' => function ($value, $format, $options) {
		return (bool) $options['model']::type($value);
	},
));

// use radium\models\BaseModel;

// if (!BaseModel::finder('random')) {
// 	BaseModel::finder('random', function($self, $params, $chain){
// 		$amount = $self::find('count', $params['options']);
// 		$offset = rand(0, $amount-1);
// 		$params['options']['offset'] = $offset;
// 		return $self::find('first', $params['options']);
// 	});
// }

?>