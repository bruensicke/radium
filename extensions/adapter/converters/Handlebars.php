<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\extensions\adapter\converters;

use Handlebars\Handlebars as Renderer;

class Handlebars extends \lithium\core\Object {

  /**
   * returns rendered content
   *
   * @param string $content input content
   * @param array $data additional data to be passed into render context
   * @param array $options an array with additional options
   * @return string content as given
   * @filter
   */
  public function get($content, $data = array(), array $options = array()) {
    $defaults = array('allowed' => true);
    $options += $defaults;
    $params = compact('content', 'data', 'options');
    return $this->_filter(__METHOD__, $params, function($self, $params) {
      $renderer = new Renderer($params['content'], $params['data']);
      return $renderer->render();
    });
  }

}
?>
