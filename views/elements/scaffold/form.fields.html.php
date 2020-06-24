<?php
use lithium\util\Inflector;
use radium\models\Configurations;

$model = $this->scaffold->model;
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

$newfields = array();
foreach ($fields as $field) {
	if (is_array($field)) {
		$newfields[] = 'GROUPSTART_'.intval(12 / count($field));
		foreach ($field as $i => $f) {
			$newfields[] = $f;
		}
		$newfields[] = 'GROUPEND';
		continue;
	}
	$newfields[] = $field;
}
$fields = $newfields;

$inCols = false;
foreach ($fields as $field) {

	if (in_array($field, $skip)) {
		continue;
	}

	if (stristr($field, 'GROUPSTART')) {
		list($groupstart, $inCols) = explode('_', $field);
		echo '<div class="row">';
		continue;
	}
	if (stristr($field, 'GROUPEND')) {
		$inCols = false;
		echo '</div>';
		continue;
	}
	if ($inCols) {
		echo sprintf('<div class="col-md-%d">', $inCols);
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
		case 'rte':
		case 'textarea':
			$options = array(
				'type' => 'textarea',
				'class' => "form-control autogrow $field",
				'rows' => 3,
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-textarea';
			}
			if ($type == 'rte') {
				$options['class'] .= ' rte';
			}
			echo $this->form->field($field, $options);
		break;

		case 'select':
			$method = Inflector::underscore(Inflector::pluralize($field));
			$options = array(
				'type' => 'select',
				'class' => "form-control $field",
				'data-switch' => $field,
				'list' => $model::$method()
			);
			if (isset($schema[$field]['null']) && $schema[$field]['null'] === true) {
				$options['empty'] = true;
			}
			if (in_array($field, $readonly)) {
				$options['type'] = 'text';
				$options['value'] = $model::$method($this->scaffold->object->$field);
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'configuration':
			$options = array(
				'type' => 'select',
				'class' => "form-control $field",
				'data-switch' => 'configuration',
				'list' => Configurations::find('list')
			);
			if (isset($schema[$field]['null']) && $schema[$field]['null'] === true) {
				$options['empty'] = true;
			}
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			if ($field == 'config_id') {
				$options['label'] = 'Configuration';
			}
			echo $this->form->field($field, $options);
		break;

		case 'ini':
			$options = array(
				'type' => 'textarea',
				'class' => 'form-control autogrow',
				'rows' => 10,
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'neon':
			$options = array(
				'type' => 'textarea',
				'class' => 'form-control autogrow',
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
				'class' => 'form-control numeric',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'double':
			$options = array(
				'type' => 'number',
				'step' => '0.01',
				'class' => 'form-control numeric',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

        case 'float':
            $options = array(
                'type' => 'number',
                'step' => '0.0000001',
                'class' => 'form-control numeric',
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
				'class' => 'form-control date',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;

		case 'list':
            $x = $binding->$field;

            $value = (is_object($x))
                ? $x->data()
                : (array)$x;
			$options = array(
				'type' => 'textarea',
				'class' => "form-control autogrow $field",
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
				'class' => 'form-control',
			);
			if (in_array($field, $readonly)) {
				$options['disabled'] = 'disabled';
				$options['class'] .= ' uneditable-input';
			}
			echo $this->form->field($field, $options);
		break;
		case 'mulselect':
			$value = array();
			$method = Inflector::underscore(Inflector::pluralize($field));

			if (is_object($this->scaffold->object->$field)) {
                $x = $this->scaffold->object->$field;
				$data = $x->data();
				$value = (!empty($data)) ? $data : array();
			}

			$options = array(
				'type' => 'select',
				'multi' => true,
				'class' => "form-control $field",
				'data-switch' => $field,
				'list' => $model::$method(),
				'value' => $value,
				'multiple' => true,
			);
			$removeFieldOptions['hidden'] = true;
			$removeFieldOptions['label'] = false;
			echo $this->form->field('removeSelect_'.$field, $removeFieldOptions);
			echo $this->form->field($field, $options);
		break;
	}

	if ($inCols) {
		echo '</div>';
	}

}
