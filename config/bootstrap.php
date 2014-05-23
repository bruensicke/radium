<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

define('RADIUM_PATH', dirname(__DIR__));

require __DIR__ . '/bootstrap/libraries.php';
require __DIR__ . '/bootstrap/validators.php';
require __DIR__ . '/bootstrap/media.php';


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