<?php
/**
 * This file is part of Handlebars-php
 * Base on mustache-php https://github.com/bobthecow/mustache.php
 *
 * PHP version 5.3
 *
 * @category  Xamin
 * @package   Handlebars
 * @author    fzerorubigd <fzerorubigd@gmail.com>
 * @author    Behrooz Shabani <everplays@gmail.com>
 * @author    Chris Gray <chris.w.gray@gmail.com>
 * @author    Ulrik Lystbaek <ulrik@bettertaste.dk>
 * @author    Dmitriy Simushev <simushevds@gmail.com>
 * @copyright 2012 (c) ParsPooyesh Co
 * @copyright 2013 (c) Behrooz Shabani
 * @copyright 2013 (c) f0ruD A
 * @license   MIT <http://opensource.org/licenses/MIT>
 * @version   GIT: $Id$
 * @link      http://xamin.ir
 */

namespace Handlebars;

/**
 * Handlebars context
 * Context for a template
 *
 * @category  Xamin
 * @package   Handlebars
 * @author    fzerorubigd <fzerorubigd@gmail.com>
 * @author    Behrooz Shabani <everplays@gmail.com>
 * @copyright 2012 (c) ParsPooyesh Co
 * @license   MIT <http://opensource.org/licenses/MIT>
 * @version   Release: @package_version@
 * @link      http://xamin.ir
 */

class Context
{

    /**
     * List of charcters that cannot be used in identifiers.
     */
    const NOT_VALID_NAME_CHARS = '!"#%&\'()*+,./;<=>@[\\]^`{|}~';

    /**
     * List of characters that cannot be used in identifiers in segment-literal
     * notation.
     */
    const NOT_VALID_SEGMENT_NAME_CHARS = "]";

    /**
     * @var array stack for context only top stack is available
     */
    protected $stack = array();

    /**
     * @var array index stack for sections
     */
    protected $index = array();

    /**
     * @var array key stack for objects
     */
    protected $key = array();

    /**
     * Mustache rendering Context constructor.
     *
     * @param mixed $context Default rendering context (default: null)
     */
    public function __construct($context = null)
    {
        if ($context !== null) {
            $this->stack = array($context);
        }
    }

    /**
     * Push a new Context frame onto the stack.
     *
     * @param mixed $value Object or array to use for context
     *
     * @return void
     */
    public function push($value)
    {
        array_push($this->stack, $value);
    }

    /**
     * Push an Index onto the index stack
     *
     * @param integer $index Index of the current section item.
     *
     * @return void
     */
    public function pushIndex($index)
    {
        array_push($this->index, $index);
    }

    /**
     * Push a Key onto the key stack
     *
     * @param string $key Key of the current object property.
     *
     * @return void
     */
    public function pushKey($key)
    {
        array_push($this->key, $key);
    }

    /**
     * Pop the last Context frame from the stack.
     *
     * @return mixed Last Context frame (object or array)
     */
    public function pop()
    {
        return array_pop($this->stack);
    }

    /**
     * Pop the last index from the stack.
     *
     * @return int Last index
     */
    public function popIndex()
    {
        return array_pop($this->index);
    }

    /**
     * Pop the last key from the stack.
     *
     * @return string Last key
     */
    public function popKey()
    {
        return array_pop($this->key);
    }

    /**
     * Get the last Context frame.
     *
     * @return mixed Last Context frame (object or array)
     */
    public function last()
    {
        return end($this->stack);
    }

    /**
     * Get the index of current section item.
     *
     * @return mixed Last index
     */
    public function lastIndex()
    {
        return end($this->index);
    }

    /**
     * Get the key of current object property.
     *
     * @return mixed Last key
     */
    public function lastKey()
    {
        return end($this->key);
    }

    /**
     * Change the current context to one of current context members
     *
     * @param string $variableName name of variable or a callable on current context
     *
     * @return mixed actual value
     */
    public function with($variableName)
    {
        $value = $this->get($variableName);
        $this->push($value);

        return $value;
    }

    /**
     * Get a available from current context
     * Supported types :
     * variable , ../variable , variable.variable , variable.[variable] , .
     *
     * @param string  $variableName variable name to get from current context
     * @param boolean $strict       strict search? if not found then throw exception
     *
     * @throws \InvalidArgumentException in strict mode and variable not found
     * @throws \RuntimeException if supplied argument is a malformed quoted string
     * @throws \InvalidArgumentException if variable name is invalid
     * @return mixed
     */
    public function get($variableName, $strict = false)
    {
        if ($variableName instanceof \Handlebars\String) {
            return (string)$variableName;
        }
        $variableName = trim($variableName);
        $level = 0;
        while (substr($variableName, 0, 3) == '../') {
            $variableName = trim(substr($variableName, 3));
            $level++;
        }
        if (count($this->stack) < $level) {
            if ($strict) {
                throw new \InvalidArgumentException(
                    'can not find variable in context'
                );
            }

            return '';
        }
        end($this->stack);
        while ($level) {
            prev($this->stack);
            $level--;
        }
        $current = current($this->stack);
        if (!$variableName) {
            if ($strict) {
                throw new \InvalidArgumentException(
                    'can not find variable in context'
                );
            }
            return '';
        } elseif ($variableName == '.' || $variableName == 'this') {
            return $current;
        } elseif ($variableName == '@index') {
            $current = $this->lastIndex();
        } elseif ($variableName == '@key') {
            $current = $this->lastKey();
        } else {
            $chunks = $this->_splitVariableName($variableName);
            foreach ($chunks as $chunk) {
                if (is_string($current) and $current == '') {
                    return $current;
                }
                $current = $this->_findVariableInContext($current, $chunk, $strict);
            }
        }
        return $current;
    }

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
            } elseif (method_exists($variable, $inside)) {
                return call_user_func(array($variable, $inside));
            }
        }

        if ($strict) {
            throw new \InvalidArgumentException('can not find variable in context');
        }

        return $value;
    }

    /**
     * Splits variable name to chunks.
     *
     * @param string $variable_name Fully qualified name of a variable.
     *
     * @throws \InvalidArgumentException if variable name is invalid.
     * @return array
     */
    private function _splitVariableName($variable_name)
    {
        $bad_chars = preg_quote(self::NOT_VALID_NAME_CHARS, '/');
        $bad_seg_chars = preg_quote(self::NOT_VALID_SEGMENT_NAME_CHARS, '/');

        $name_pattern = "(?:[^" . $bad_chars . "\s]+)|(?:\[[^" . $bad_seg_chars . "]+\])";
        $check_pattern = "/^((" . $name_pattern . ")\.)*(" . $name_pattern  . ")\.?$/";
        $get_pattern = "/(?:" . $name_pattern . ")/";

        if (!preg_match($check_pattern, $variable_name)) {
            throw new \InvalidArgumentException('variable name is invalid');
        }

        preg_match_all($get_pattern, $variable_name, $matches);

        $chunks = array();
        foreach ($matches[0] as $chunk) {
            // Remove wrapper braces if needed
            if ($chunk[0] == '[') {
                $chunk = substr($chunk, 1, -1);
            }
            $chunks[] = $chunk;
        }

        return $chunks;
    }

}
