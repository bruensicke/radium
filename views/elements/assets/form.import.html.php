<?php
echo $this->Form->field('mode', array(
	'type' => 'select',
	'label' => 'Choose mode',
	'class' => 'form-control',
	'list' => array(
		'keep' => 'keep all records, import only new',
		'overwrite' => 'overwrite existing records',
		'remove' => 'remove all existing records before import',
	),
));
echo $this->Form->checkbox('validate', array(
	'label' => 'Only import, if validation passes',
));
echo '<br>';
echo $this->Form->checkbox('strict', array(
	'label' => 'Only import fields defined in schema',
));
echo '<br>';
