<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2010, Michael Hüneburg
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\storage;

use lithium\core\Libraries;
use lithium\util\StringDeprecated;

/**
 * Class for setting, getting and clearing flash messages. Use this class inside your
 * controllers to set messages for your views.
 *
 * {{{
 * // Controller
 * if (!$data) {
 *     FlashMessage::write('Invalid data.');
 * }
 * // or
 * if (!$post) {
 * 	return $this->redirect('Posts::index', array('message' => 'Post not found!'));
 * }
 *
 * // View
 * <?=$this->flashMessage->show(); ?>
 * }}}
 */
class FlashMessage extends \lithium\core\StaticObject {

	/**
	 * Class dependencies.
	 *
	 * @var array
	 */
	protected static $_classes = array(
		'session' => 'lithium\storage\Session'
	);

	/**
	 * Configuration directives for writing, storing, and rendering flash messages.
	 *
	 * @var array
	 */
	protected static $_session = array(
		'config' => 'default',
		'base' => null
	);

	/**
	 * The library containing the `messages.php` config file.
	 *
	 * @var array
	 */
	protected static $_library = true;

	/**
	 * Stores message keys.
	 *
	 * @var array
	 */
	protected static $_messages = null;

	/**
	 * Used to set configuration parameters for `FlashMessage`.
	 *
	 * @see radium\extensions\storage\FlashMessage::$_config
	 * @param array $config Possible key settings:
	 *              - `'classes'` _array_: Sets class dependencies (i.e. `'session'`).
	 *              - `'session'` _array_: Configuration for accessing and manipulating session
	 *                data.
	 * @return array If no parameters are passed, returns an associative array with the current
	 *         configuration, otherwise returns `null`.
	 */
	public static function config(array $config = array()) {
		if (!$config) {
			return array('session' => static::$_session) + array('classes' => static::$_classes);
		}

		foreach ($config as $key => $val) {
			$key = "_{$key}";
			if (isset(static::${$key})) {
				static::${$key} = is_array($val) ? $val + static::${$key} : $val;
			}
		}
	}

	/**
	 * Binds the messaging system to a controller to enable `'message'` option flags in various
	 * controller methods, such as `render()` and `redirect()`.
	 *
	 * @param object $controller An instance of `lithium\action\Controller`.
	 * @param array $options Options.
	 * @return object Returns the passed `$controller` instance.
	 */
	public static function bindTo($controller, array $options = array()) {
		if (!method_exists($controller, 'applyFilter')) {
			return $controller;
		}

		$controller->applyFilter('redirect', function($self, $params, $chain) use ($options) {
			$options =& $params['options'];
			if (!isset($options['message'])) {
				return $chain->next($self, $params, $chain);
			}

			FlashMessage::write($options['message']);
			unset($options['message']);
			return $chain->next($self, $params, $chain);
		});

		return $controller;
	}

	/**
	 * Writes a flash message.
	 *
	 * @todo Add closure support to messages
	 * @param mixed $message Message the message to be stored.
	 * @param array $attrs Optional attributes that will be available in the view.
	 * @param string $key Optional key to store multiple flash messages.
	 * @return boolean True on successful write, false otherwise.
	 */
	public static function write($message, array $attrs = array(), $key = 'flash_message') {
		$session = static::$_classes['session'];
		$key = static::_key($key);
		$name = static::$_session['config'];

		if (static::$_messages === null) {
			$path = Libraries::get(static::$_library, 'path') . '/config/messages.php';
			static::$_messages = file_exists($path) ? include $path : array();
		}

		$message = static::_translate($message, $attrs);

		return $session::write($key, compact('message', 'attrs'), compact('name'));
	}

	/**
	 * Recursive message translation.
	 *
	 * @param mixed $message Message the message to be stored.
	 * @param array $attrs Optional attributes that will be available in the view.
	 * @return array
	 */
	protected static function _translate($message, array $attrs) {
		if (is_string($message)) {
			if (isset(static::$_messages[$message])) {
				$message = static::$_messages[$message];
			}
			$message = StringDeprecated::insert($message, $attrs);
		} elseif (is_array($message)) {
			foreach ($message as $index => $value) {
				$message[$index] = static::_translate($value, $attrs);
			}
		}
		return $message;
	}
	/**
	 * Reads a flash message.
	 *
	 * @param string [$key] Optional key.
	 * @return array The stored flash message.
	 */
	public static function read($key = 'flash_message') {
		$session = static::$_classes['session'];
		$key = static::_key($key);
		return $session::read($key, array('name' => static::$_session['config']));
	}

	/**
	 * Delete a flash messages from the session.
	 *
	 * @return boolean
	 */
	public static function clear($key = 'flash_message') {
		$session = static::$_classes['session'];
		$key = static::_key($key);
		return $session::delete($key, array('name' => static::$_session['config']));
	}

	/**
	 * Reset the class.
	 */
	public static function reset() {
		static::$_library = true;
		static::$_messages = null;
		static::$_classes = array(
			'session' => 'lithium\storage\Session'
		);
		static::$_session = array(
			'config' => 'default',
			'base' => null
		);
	}

	/**
	 * Helper for building the key
	 *
	 * @param string $key The key.
	 * @return string The complete key.
	 */
	protected static function _key($key) {
		$base = static::$_session['base'];
		return ($base ? "{$base}." : '') . $key;
	}
}

?>
