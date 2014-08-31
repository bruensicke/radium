<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2010, Michael Hüneburg
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use lithium\template\TemplateException;

/**
 * Helper to output flash messages.
 *
 * @see radium\extensions\action\FlashMessage
 */
class FlashMessage extends \lithium\template\Helper {

	/**
	 * Holds the instance of the flash message storage class
	 *
	 * @see \radium\extensions\storage\FlashMessage
	 */
	protected $_classes = array(
		'storage' => 'radium\extensions\storage\FlashMessage'
	);

	/**
	 * Outputs a flash message using a template. The message will be cleared afterwards.
	 * With defaults settings it looks for the template
	 * `app/views/elements/flash_message.html.php`. If it doesn't exist, the  plugin's view
	 * at `radium/views/elements/flash_message.html.php` will be used. Use this
	 * file as a starting point for your own flash message element. In order to use a
	 * different template, adjust `$options['type']` and `$options['template']` to your needs.
	 *
	 * @param string [$key] Optional message key.
	 * @param array [$options] Optional options.
	 *              - type: Template type that will be rendered.
	 *              - template: Name of the template that will be rendered.
	 *              - data: Additional data for the template.
	 *              - options: Additional options that will be passed to the renderer.
	 * @return string Returns the rendered template.
	 */
	public function render($key = 'flash_message', array $options = array()) {
		$defaults = array(
			'type' => 'element',
			'template' => 'radium/flash_message',
			'data' => array(),
			'options' => array()
		);
		$options += $defaults;

		$storage = $this->_classes['storage'];
		$view = $this->_context->view();
		$type = array($options['type'] => $options['template']);

		if (!$flash = $storage::read($key)) {
			return;
		}
		$data = $options['data'] + array('message' => $flash['message']) + $flash['attrs'];
		$storage::clear($key);

		try {
			return $view->render($type, $data, $options['options']);
		} catch (TemplateException $e) {
			return $view->render($type, $data, array('library' => 'radium'));
		}
	}
}

?>