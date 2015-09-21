<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Configurations;
use lithium\template\TemplateException;

use lithium\core\Environment;
use lithium\util\String;

class Widget extends \lithium\template\Helper {

	/**
	 * renders a list of widgets according to a defined structure
	 *
	 * {{{
	 * contents/view
	 * configurations/details
	 * }}}
	 *
	 * {{{
	 * contents/view
	 * 	slug: foo
	 * configurations/details
	 * 	slug: bar
	 * }}}
	 *
	 * {{{
	 * contents/view
	 * configuration_slug:
	 * }}}
	 *
	 * {{{
	 * -
	 * 	widget: contents/view
	 *  slug: foo
	 *  target: a
	 * -
	 * 	widget: configurations/details
	 *  slug: foo
	 *  target: b
	 * }}}
	 *
	 * @param array $widgets
	 * @param array $options additional options:
	 *              - `prefix`: a string that is prefixed in front of widget name
	 *              - `pattern`: pattern of slug to search within configurations for
	 *                           additional, defaults to `widget.{:name}`
	 *              - `seperator`: Character to be used to join widgets, defaults to `\n`
	 * @return bool|string
	 */
	public function render($widgets = array(), array $options = array()) {
		$defaults = array('seperator' => "\n", 'pattern' => 'widget.{:name}', 'prefix' => '');
		$options += $defaults;

		if (empty($widgets) && $this->_context->page) {
			$widgets = $this->_context->page->widgets();
		}

		$result = array();
		foreach ((array) $widgets as $key => $value) {
			$widget = (is_array($value) || is_null($value)) ? $key : $value;
			$data = (is_array($value)) ? $value : array();

			if (isset($data['widget'])) {
				$widget = $data['widget'];
			}
			$name = $options['prefix'].$widget;

			//Whow is not using widget configurations ATM.
			//Therefore we disabled querying the MongoDB for each widget.
			//
			//$config = Configurations::get(String::insert($options['pattern'], compact('name')));
			$config = false;

			$result[] = ($config)
				? $this->render($config, $options)
				: $this->parse($name, $data, $options);
		}
		return implode($options['seperator'], array_filter($result));
	}

	/**
	 * renders a given widget element with given data
	 *
	 * @param string $widget name of widget to render
	 * @param array $data additional data to be passed into element
	 * @param array $options additional options:
	 *              - `hb`: set to false to disable handlebars rendering
	 *              - `target`: a string that defines which widgets to render, depending on widgets
	 *                          target parameter
	 * @return string the rendered markup from all widgets
	 */
	protected function parse($widget, array $data = array(), array $options = array()) {
		$defaults = array('target' => null, 'hb' => true);
		$options += $defaults;

		if (($options['target']) && (!(!empty($data['target'])) || $data['target'] != $options['target'])) {
			return;
		}
		if (!$options['hb']) {
			return $this->_context->view()->render(compact('widget'), $data, $options);
		}

		$hb = $this->_context->helper('handlebars');
		try {
			return $hb->render(sprintf('../widgets/%s', $widget), $data, $options);
		} catch (TemplateException $e) {
			return (Environment::is('development')) ? $e->getMessage() : '';
			// return $hb->render(sprintf('../widgets/%s', $widget), $data, $options);
		}
		return '';
	}

	/**
	 * renders only those widgets, that are targeted with name `$target`
	 *
	 * @see radium\extensions\helper\Widget::render()
	 * @param string $target name of target to render widgets for
	 * @param array $widgets can be passed widgets, according to Widget->render()
	 * @return string the rendered markup from all widgets
	 */
	public function target($target, $widgets = array()) {
		return $this->render($widgets, compact('target'));
	}

}