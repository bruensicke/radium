<?php
use radium\models\Configurations;

$slug = (isset($slug)) ? $slug : '';
$configuration = (isset($configuration)) ? $configuration : null;

if (is_null($configuration) && !empty($slug)) {
	$configuration = Configurations::load($slug);
}

if (!$configuration) {
	return;
}

switch($configuration->type) {
	case 'navigation':
		echo $this->Navigation->render($configuration->val());
		break;
	case 'ini':
	case 'json':
	case 'neon':
	case 'array':
		echo $this->widget->render(array('radium/data' => array('data' => $configuration->val(null, array('flat' => true)))));
		break;
	case 'list':
		echo '<div class="well">';
		echo $this->widget->render(array('radium/list' => array('data' => $configuration->val(null, array('flat' => true)))));
		echo '</div>';
		break;
	case 'string':
		echo '<p class="well">'.$configuration->value.'</p>';
		break;
	case 'boolean':
		$val = $configuration->val();
		$label = ($val) ? 'true' : 'false';
		echo '<span class="label label_'.$label.'">'.$label.'</span>';
		break;
}
?>