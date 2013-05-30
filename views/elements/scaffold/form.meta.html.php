<?php
$binding = $this->form->binding();
$schema = $binding->schema();
$fields = $schema->names();

echo (in_array('status', $fields)) ?
	$this->form->field('status', array(
		'type' => 'select',
		'class' => 'input-xlarge',
		'data-switch' => 'status',
		'list' => $scaffold['model']::status()
	)) : '';

echo (in_array('type', $fields)) ?
	$this->form->field('type', array(
		'type' => 'select',
		'class' => 'input-xlarge',
		'data-switch' => 'type',
		'list' => $scaffold['model']::types()
	)) : '';

echo (in_array('name', $fields)) ?
	$this->form->field('name', array(
		'class' => 'input-xlarge',
	)) : '';

echo (in_array('slug', $fields)) ?
	$this->form->field('slug', array(
		'class' => 'input-xlarge slug',
	)) : '';

echo (in_array('notes', $fields)) ?
	$this->form->field('notes', array(
		'type' => 'textarea',
		'class' => 'input-xlarge',
		'rows' => 3,
	)) : '';

echo (in_array('configuration', $fields)) ?
	$this->form->field('configuration', array(
		'type' => 'select',
		'class' => 'input-xlarge',
		'data-switch' => 'configuration',
		'list' => Configurations::find('list')
	)) : '';
?>