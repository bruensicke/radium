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
echo $this->Form->field('validate', array(
	'type' => 'checkbox',
	'label' => 'Only import, if validation passes',
));
echo $this->Form->field('strict', array(
	'type' => 'checkbox',
	'label' => 'Only import fields defined in schema',
));
