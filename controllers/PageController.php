<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\controllers;

class PageController extends BaseController {

   	public function _init() {
        parent::_init();

        if (empty($this->request->page)) {
        	return;
        }
        $this->_page = $this->request->page;
        $this->_render['layout'] = $this->_page->layout;
        $this->_render['template'] = $this->_page->template;
    }

	public function view() {
	}
}
