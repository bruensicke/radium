<?php
foreach ($models as $model) {
	echo $this->Form->field($model, array(
		'type' => 'checkbox',
		'label' => $model,
		'value' => $model,
	));
}
?>
