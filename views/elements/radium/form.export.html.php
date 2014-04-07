<?php
foreach ($models as $model) {
	echo $this->Form->checkbox($model, array(
		'div' => 'form-control',
		'label' => $model,
	));
	echo '<br>';
}

