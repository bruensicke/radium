<?php

namespace radium\extensions\helper;

class Html extends \lithium\template\helper\Html {

	/**
	 * overwritten link method, to support additional options
	 *
	 * @see lithium\template\helper\Html
	 * @param string $title The content to be wrapped by an `<a />` tag,
	 *               or the `title` attribute of a meta-link `<link />`.
	 * @param mixed $url Can be a string representing a URL relative to the base of your Lithium
	 *              application, an external URL (starts with `'http://'` or `'https://'`), an
	 *              anchor name starting with `'#'` (i.e. `'#top'`), or an array defining a set
	 *              of request parameters that should be matched against a route in `Router`.
	 * @param array $options The additional options are:
	 *              - `'icon'` _string_: adds an icon left the text, using font-awesome i-tag
	 *              will automatically set `escape` option to false.
	 * @return string Returns an `<a />` or `<link />` element.
	 */
	public function link($title, $url = null, array $options = array()) {
		if (isset($options['icon'])) {
			$icon = $options['icon'];
			$title = sprintf('<i class="fa fa-%s"></i> %s', $icon, $title);
			$options['escape'] = false;
		}
		return parent::link($title, $url, $options);
	}

}
