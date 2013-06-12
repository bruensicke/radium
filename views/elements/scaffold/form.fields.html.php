<?php
use lithium\util\Inflector;

$binding = $this->form->binding();
$schema = $binding->schema();
$fields = isset($fields)
	? $fields
	: $schema->names();
$skip = isset($skip)
	? $skip
	: array();
$readonly = isset($readonly)
	? $readonly
	: array();
	$readonly[]= 'type';

foreach ($fields as $index => $field) {
	if (in_array($field, $skip)) {
		continue;
	}

	switch ($field) {
		case 'type':
		case 'status':
			$type = 'select';
		break;
		case 'notes':
			$type = 'textarea';
		break;
		default:
			$type = $schema->type($field);
	}

	switch($type) {
		case 'textarea':
			$options = array(
				'type' => 'textarea',
				'class' => "input-block-level autogrow $field",
				'rows' => 3,
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
			echo $this->form->field($field, $options);
		break;

		case 'select':
			$method = Inflector::pluralize($field);
			$options = array(
				'type' => 'select',
				'class' => "input-block-level $field",
				'data-switch' => $field,
				'list' => $scaffold['model']::$method()
			);
			if (in_array($field, $readonly)) {
				$options['type'] = 'input';
				$options['class'] .= ' uneditable-input';
				$options['value'] = $scaffold['model']::$method($this->scaffold->object->$field);
				$options += array(
					'disabled' => 'disabled',
				);
			}
			echo $this->form->field($field, $options);
		break;

		case 'configuration':
			$options = array(
				'type' => 'select',
				'class' => "input-block-level $field",
				'data-switch' => 'configuration',
				'list' => Configurations::find('list')
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
			echo $this->form->field($field, $options);
		break;

		case 'ini':
			$options = array(
				'type' => 'textarea',
				'class' => 'input-block-level autogrow',
				'rows' => 10,
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
			echo $this->form->field($field, $options);
		break;

		case 'integer':
			$options = array(
				'class' => 'input-mini numeric',
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
			echo $this->form->field($field, $options);
		break;

		case 'date':
			// TODO: datepicker
			$options = array(
				'class' => 'input-block-level date',
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
		break;

		case 'string':
			$options = array(
				'class' => 'input-block-level',
			);
			if (in_array($field, $readonly)) {
				$options += array('disabled' => 'disabled');
			}
			echo $this->form->field($field, $options);
	}
}
?>