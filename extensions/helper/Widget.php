<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Configurations;
use \radium\util\Widgets;

use lithium\core\Environment;
use lithium\util\String;
use lithium\template\TemplateException;
use Exception;

class Widget extends \lithium\template\Helper {

	public function library() {
		$libraries = Widgets::find();
		$hb = $this->_context->helper('handlebars');
		// var_dump($libraries);exit;
		try {
			return $hb->element('radium/widgets.library', compact('libraries'));
		} catch (TemplateException $e) {
			return '';
		} catch (Exception $e) {
			return '';
		}
		return '';

// foreach ( as $key => $widgets) {
//     $middle .= '<li class="dd-item" data-id="'.$key.'"><div class="dd-handle">'.$key.' </div><ol>';
//     foreach ($widgets as $widget) {

//     	// $foo = array(
//     	// 	'widget' => $widget.'.admin',
//     	// 	'index' => 0,
//     	// );
//         $middle .= $getWidget($this, $widget, 0);
//     }
//     $middle .= '</ol></li>';
// }
	}

	public function admin($widgets = array(), array $options = array()) {

	}

	/**
	 * renders a list of widgets according to a defined structure
	 *
	 * {{{
	 * -
	 * 	widget: configuration:foobar
	 * -
	 * 	widget: app:contents/view
	 *  target: a
	 *  slug: foo (optional)
	 * -
	 * 	widget: radium:configurations/details
	 *  target: b
	 *  foo: bar (optional)
	 * }}}
	 *
	 * @param array $widgets
	 * @param array $options additional options:
	 *              - `separator`: Character to be used to join widgets, defaults to `\n`
	 * @return bool|string
	 */
	public function render($widgets = array(), array $options = array()) {
		$defaults = array('separator' => "\n");
		$options += $defaults;

		if (empty($widgets) && $this->_context->page) {
			$widgets = $this->_context->page->widgets();
		}

		$result = array();
		foreach ((array) $widgets as $key => $data) {
			if (!isset($data['widget'])) {
				continue;
			}
			$result[] = $this->parse($data['widget'], $data, $options);
		}

		return implode($options['separator'], $result);
	}

	/**
	 * renders a given widget element with given data
	 *
	 * @param string $widget name of widget to render
	 * @param array $data additional data to be passed into element
	 * @param array $options additional options:
	 *              - `prefix`: a string that is prefixed in front of widget name
	 *              - `suffix`: a string that is suffixed after widget name
	 *              - `pattern`: pattern of slug to search within configurations for
	 *                           additional, defaults to `widget.{:name}`
	 *              - `hb`: set to false to disable handlebars rendering
	 *              - `target`: a string that defines which widgets to render, depending on widgets
	 *                          target parameter
	 * @return string the rendered markup from all widgets
	 */
	public function parse($widget, array $data = array(), array $options = array()) {
		$defaults = array('target' => null, 'hb' => true, 'prefix' => '', 'suffix' => '',
			'pattern' => 'widget.{:widget}', 'inc' => true);
		$options += $defaults;

		if (!stristr($widget, ':')) {
			$library = null;
		} else {
			list($library, $widget) = explode(':', $widget, 2);
		}

		if ($library == 'configuration') {
			$config = Configurations::get(String::insert($options['pattern'], compact('widget')));
			return (empty($config)) ? '' : $this->render($config);
		}

		$include = $widget.'.inc'; // TODO: include before
		$widget = $options['prefix'].$widget.$options['suffix'];

		if (($options['target']) && (!(!empty($data['target'])) || $data['target'] != $options['target'])) {
			return;
		}
		if (!$options['hb']) {
			try {
				return $this->_context->view()->render(compact('widget'), $data, $options);
			} catch (Exception $e) {
				return '';
			}
		}

		$hb = $this->_context->helper('handlebars');
		try {
			// debug(sprintf('../widgets/%s', $widget));
			$foo = $hb->render(sprintf('../widgets/%s', $widget), $data, $options);
			// var_dump($foo);exit;
			return $foo;
		} catch (TemplateException $e) {
			return '';
		} catch (Exception $e) {
			return '';
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