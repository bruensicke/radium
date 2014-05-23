<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\template;

class View extends \lithium\template\View {

	protected $_processes = array(
		// 'all' => array('template', 'layout'),
		'all' => array('template'),
		'template' => array('template'),
		'element' => array('element')
	);
	protected $_steps = array(
		'template' => array('path' => 'template'),
		'element' => array('path' => 'element')
		// 'template' => array('path' => 'template', 'capture' => array('context' => 'content')),
		// 'layout' => array(
		// 	'path' => 'layout', 'conditions' => 'layout', 'multi' => true, 'capture' => array(
		// 		'context' => 'content'
		// 	)
		// ),
		// 'element' => array('path' => 'element')
	);
}

?>