<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\template;

use lithium\util\String;
use lithium\core\Libraries;

/**
 * Custom Context Handler for Handlebars Template rendering.
 *
 * @see Handlebars\Template
 */
class HandlebarsContext extends \Handlebars\Context {

    /**
     * Check if $variable->$inside is available
     *
     * @param mixed   $variable variable to check
     * @param string  $inside   property/method to check
     * @param boolean $strict   strict search? if not found then throw exception
     *
     * @throws \InvalidArgumentException in strict mode and variable not found
     * @return boolean true if exist
     */
    private function _findVariableInContext($variable, $inside, $strict = false)
    {
    	debug($variable);exit;
        $value = '';
        if (($inside !== '0' && empty($inside)) || ($inside == 'this')) {
            return $variable;
        } elseif (is_array($variable)) {
            if (isset($variable[$inside]) || array_key_exists($inside, $variable)) {
                return $variable[$inside];
            } elseif ($inside == "length") {
                return count($variable);
            }
        } elseif (is_object($variable)) {
            if (isset($variable->$inside)) {
                return $variable->$inside;
            } elseif (is_callable(array($variable, $inside))) {
                return call_user_func(array($variable, $inside));
            }
        }

        if ($strict) {
            throw new \InvalidArgumentException('can not find variable in context');
        }

        return $value;
    }
}