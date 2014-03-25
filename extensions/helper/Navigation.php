<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Configurations;
use lithium\util\Inflector;
use lithium\template\TemplateException;
use lithium\util\Set;

class Navigation extends \lithium\template\Helper {

	/**
	 * Generated a navigation list based on the given parameters.
	 * If a string is given, it looks for a Configuration with this slug and takes the array in it to render
	 * If an array is given, it just renders the navigation with this array.
	 *
	 * The array should look like this:
	 *
	 *	Array (
	 *		0 => Array (
	 *			'name' => 'Users'   // optional, if not provided, humanized url will be used
	 *			'icon' => 'user'    // optional, font-awesome icon name
	 *			'url' =>  '/users'  // mandatory
	 *		),
	 *		1 => Array (
	 *			'name' => 'Contents'
	 *			'icon' => 'file-text'
	 *			'url' =>  '/contents'
	 *		)
	 * 	)
	 *
	 * @param string|array $nav slug or array containing the navigation items
	 * @return string HTML navigation list
	 */
	public function render($nav) {
		$navigation = array();
		if ($nav instanceof \lithium\data\Entity) {
			$navigation['caption'] = $nav->name;
			$nav = $nav->val();
		}
		if (is_array($nav)) {
			foreach ($nav as $navitem) {
				$navitem = $this->_item($navitem);
				if (!empty($navitem['children'])) {
					foreach ($navitem['children'] as $id => $child) {
						$child = $this->_item($child);
						$navitem['children'][$id] = $child;
					}
				}
				$navigation['items'][] = $navitem;
			}
		}
		elseif (!empty($nav) && is_string($nav)) {
			$this->render(Configurations::get($nav));
		}
		return $this->_element('navigation', $navigation);
	}

	public function group($name) {
		$configs = Configurations::search(sprintf('nav\.%s\.', $name));
		$returnvalue = '';
		foreach($configs as $nav) {
			$returnvalue .= $this->render($nav);
		}
		return $returnvalue;
	}

	/**
	 * shortcut function to render elements with current view context
	 *
	 * it looks by default into `views/elements/<plural_model>/<name>`, first at app-level
	 * and falling back to `views/elements/scaffold/<name>`, also first at app-level.
	 *
	 * if at app-level nothing is found, radium is used as fallback.
	 *
	 * @param string $name name of element template to render
	 * @param array $data additional data to be put into view context
	 * @param array $options additional options to be put into view->render() call
	 * @return string rendered html of element template
	 */
	private function _element($name, array $data = array(), array $options = array()) {
		$element = sprintf('%s/%s', 'templates', $name);
		$data = $this->_data($data, $options);
		$hb = $this->_context->helper('handlebars');
		try {
			return $hb->render($element, $data, $options);
		} catch (TemplateException $e) {
			$element = sprintf('radium/templates/%s', $name);
			return $hb->render($element, $data, $options);
		}
	}

	/**
	 * allows merging of data from context with given data
	 *
	 * @param array $data additional data to be put into view
	 * @param array $options additional options
	 *        - `merge`: set to false to disable merging view data with context
	 * @return array
	 */
	private function _data($data = array(), array $options = array()) {
		$defaults = array('merge' => true);
		$options += $defaults;
		if ($options['merge'] === false) {
			return $data;
		}
		if (!empty($data)) {
			return Set::merge($this->_context->data(), $data);
		}
		return $this->_context->data();
	}


	private function _item($navitem) {
		$context = $this->_context;
		$navitem['url']   = (empty($navitem['url']) && !empty($navitem['name']))
			? Inflector::tableize($navitem['name'])
			: $navitem['url'];
		$navitem['name']   = (empty($navitem['name']) && !empty($navitem['url']))
			? Inflector::humanize(basename($navitem['url']))
			: $navitem['name'];
		$navitem['active'] = stristr($navitem['url'], $context->scaffold->controller);
		$navitem['link']   = $context->url($navitem['url']);
		$navitem['badge']  = empty($navitem['badge'])
			? null
			: $this->_badge($navitem['badge']);
		return $navitem;
	}

	private function _badge($badge) {
		$returnvalue = array(
			'value' => '',
			'shape' => '',
			'color' => 'primary'
		);
		if (!is_array($badge)) {
			$temp = $badge;
			$badge = array(
				'value' => $temp
			);
		} else {
			$badge = $badge[0];
		}
		$returnvalue = array_merge($returnvalue, $badge);
		return $returnvalue;
	}

}
