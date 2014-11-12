<div class="actions pull-right btn-group">
<?php
$mode = 'btn'; // to allow for easier extending use-cases, e.g. ordered lists of links and so on
$prefix = 'btn-'; // see above
$callback = function(&$val, $foo, $prefix) {
	$val = sprintf('%s%s', $prefix, $val);
};
$actions = $this->scaffold->actions();
foreach ($actions as $action => $options) {
	$name = (!empty($options['name']))
		? $options['name']
		: $action;
	unset($options['name']);

	$classes = (!empty($options['class'])) ? explode(' ', 'default '.$options['class']) : array('default');
	array_walk($classes, $callback, $prefix);
	array_unshift($classes, $mode);
	$options['class'] = implode(' ', $classes);
	echo $this->html->link($name, $this->scaffold->action($action), $options);
}
?>
</div>
