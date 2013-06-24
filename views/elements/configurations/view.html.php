<?php
switch($configuration->type) {
	case 'ini':
	case 'neon':
	case 'array':
		echo $this->scaffold->mustache('data', array('data' => $this->mustache->data($configuration->val())));
		break;
	case 'list':
		echo '<div class="well">';
		echo $this->scaffold->mustache('list', array('data' => $configuration->val()));
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