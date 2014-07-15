<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\helper;

use radium\models\Configurations;
use radium\util\Neon;
use lithium\util\Inflector;
use lithium\template\TemplateException;
use lithium\util\String;
use lithium\util\Set;

class Navigation extends \lithium\template\Helper {

	/**
	 * Generated a navigation list based on the given parameters.
	 * If a string is given, it looks for a Configuration with this slug and takes the array in it to render.
	 * If an array is given, it just renders the navigation with this array.
	 *
	 * The array should look like this:
	 *
	 *	Array (
	 *		0 => Array (
	 *			'name' => 'Users'   // optional(1), if not provided, humanized url will be used
	 *			'icon' => 'user'    // optional, font-awesome icon name (without the trailing ´fa-´
	 *			'url' =>  '/users'  // optional(1), if not provided, lower case ´name´ will be used (in this case /users)
	 * 			'badge' => 4		// optional, will render a ´4´ in a blue circle behind the navigation name
	 *		),
	 *		1 => Array (
	 *			'name' => 'Contents'
	 * 			'badge' => array(			// badge can also be an array with additional data
	 * 				'value' => '4', 		// mandatory
	 * 				'color' => 'primary'    // optional, can be default (gray), primary (blue),
	 * 										// success (green), info (turquoise), warning (yellow), danger (red)
	 * 			)
	 *		),
	 *		2 => Array (					// this will render a expandable submenu ´Contents´
	 *			'name' => 'Contents'		// with the items Posts and Images inside it.
	 * 			'children' => Array(
	 * 				[0] => Array (
	 *					'name' => 'Posts'
	 *					'icon' => 'page'
	 *				),
	 * 				[1] => Array (
	 *					'name' => 'Images'
	 *					'icon' => 'image'
	 *				),
	 * 			)
	 *		)
	 * 	)
	 *
	 * (1) either ´name´ or ´url´ must be provided
	 *
	 * @param string|array $nav slug or array containing the navigation items
	 * @param array $options additional options:
	 *        - `caption`: allows overwriting/setting caption of navigation
	 * @return string HTML navigation list
	 */
	public function render($nav, array $options = array()) {
		$defaults = array('caption' => false);
		$options += $defaults;

		if (is_string($nav)) {
			return $this->render(Configurations::get($nav));
		}

		$navigation = array();
		if ($nav instanceof \lithium\data\Entity) {
			$navigation['caption'] = $nav->name;
			$nav = $nav->val();
		}

		if (!is_array($nav)) {
			return false;
		}

		if ($options['caption']) {
			$navigation['caption'] = $options['caption'];
		}


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
		return $this->_element('navigation', $navigation);
	}

	/**
	 * Renders a group of navigations defined as Configurations.
	 *
	 * A Configuration with a type ´navigation´ must follow this naming conventions:
	 * nav.{name of navigation group}.{name of navigation}
	 * e.g. nav.sidebar.main, nav.sidebar.mailplugin, ...
	 *
	 * $this->Navigation->group('sidebar') will render all Configurations with a slug starting with
	 * ´nav.sidebar.´ that are of type `navigation`. In addition it renders all navigations based
	 * on files from all loaded libraries, that have files at {:library}/data/nav/{:name}.foo.neon
	 *
	 * @param string $name part of a navigation slug
	 * @param array $options additional options:
	 *        - `pattern`: pattern of slug to search configurations of, defaults to `nav.{:name}.`
	 *        - `seperator`: glue-character to implode all navigations together with
	 *        - `db`: set to false to avoid database-based rendering of navigations (configurations)
	 *        - `files`: set to false to avoid file-based rendering of navigations
	 * @return string all navigations
	 */
	public function group($name, array $options = array()) {
		$defaults = array(
			'pattern' => 'nav.{:name}.', 'seperator' => "\n", 'files' => true, 'db' => 'true');
		$options += $defaults;

		$result = array();

		if ($options['db']) {
			$configs = Configurations::search(String::insert($options['pattern'], compact('name')));
			if ($configs) {
				foreach($configs as $nav) {
					$result[] = $this->render($nav);
				}
			}
		}

		if (!$options['files']) {
			return implode($options['seperator'], $result);
		}

		$conditions = array('slug' => $name);
		$files = Neon::find(array('source' => 'nav', 'key' => 'slug'), 'all', compact('conditions'));
		if ($files) {
			foreach($files as $file => $nav) {
				$caption = Inflector::humanize(str_replace(sprintf('%s.', $name), '', $file));
				$result[] = $this->render($nav, compact('caption'));
			}
		}
		return implode($options['seperator'], $result);
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
		// $data = $this->_data($data, $options);
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


	/**
	 * creates all necessary array keys for rendering a menu/list item
	 *
	 * @param array $navitem all available data for a single navigation item
	 * @return array navitem filled with all needed keys
	 */
	private function _item($navitem) {
		$context = $this->_context;
		$navitem['url']   = (empty($navitem['url']) && !empty($navitem['name']))
			? '/' . Inflector::tableize($navitem['name'])
			: $navitem['url'];
		$navitem['name']   = (empty($navitem['name']) && !empty($navitem['url']))
			? Inflector::humanize(basename($navitem['url']))
			: $navitem['name'];
		$navitem['active'] = (bool) stristr($navitem['url'], $context->scaffold->slug);
		$navitem['link']   = $context->url($navitem['url']);
		$navitem['badge']  = empty($navitem['badge'])
			? null
			: $this->_badge($navitem['badge']);
		return $navitem;
	}

	/**
	 * creates all necessary array keys for a badge subkey of a menu item
	 *
	 * @param array $badge all available data for a single badge
	 * @return array filled with all needed keys for a badge
	 */
	private function _badge($badge) {
		$returnvalue = array(
			'value' => '',
			'color' => 'primary'
		);
		if (!is_array($badge)) {
			$temp = $badge;
			$badge = array(
				'value' => $temp
			);
		} elseif (isset($badge[0])) {
			$badge = $badge[0];
		}
		$returnvalue = array_merge($returnvalue, $badge);
		return $returnvalue;
	}

}
