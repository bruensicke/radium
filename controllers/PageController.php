<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

use lithium\action\DispatchException;

class PageController extends BaseController {

	public function view() {
		if (empty($this->request->page)) {
			throw new DispatchException('Could not find page.');
		}
		$this->_page = $this->request->page;
		$this->_render['layout'] = $this->_page->layout;
		$this->_render['template'] = $this->_page->template;
	}
}
