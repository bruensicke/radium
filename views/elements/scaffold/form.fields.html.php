<?php
use lithium\util\Inflector;
use radium\models\Configurations;

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
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-textarea';
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
				$options['type'] = 'text';
				$options['value'] = $scaffold['model']::$method($this->scaffold->object->$field);
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
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
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
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
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'integer':
			$options = array(
				'type' => 'number',
				'class' => 'input-mini numeric',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'date':
			// TODO: datepicker
			$options = array(
				'class' => 'input-block-level date',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
		break;

		case 'list':
			$value = (is_object($binding->$field))
				? $binding->$field->data()
				: (array) $binding->$field;
			$options = array(
				'type' => 'textarea',
				'class' => "input-block-level autogrow $field",
				'rows' => 3,
				'value' => implode("\n", $value),
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-textarea';
			}
			echo $this->form->field($field, $options);
		break;

		case 'string':
			$options = array(
				'class' => 'input-block-level',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;
	}
}
?>