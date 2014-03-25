<?php
$configuration = $this->scaffold->object;
switch($configuration->type) {
	case 'navigation':
		echo $this->Navigation->render($configuration->val());
		break;
	case 'ini':
	case 'json':
	case 'neon':
	case 'array':
		echo $this->scaffold->render('data', array('data' => $configuration->val(null, array('flat' => true))));
		break;
	case 'list':
		echo '<div class="well">';
		echo $this->scaffold->render('list', array('data' => $configuration->val(null, array('flat' => true))));
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