<?php
use radium\models\Contents;
use radium\models\Configurations;
use lithium\util\Inflector;
$binding = $this->form->binding();
if (!$binding) {
	// TODO: show useful message, explaining
	return;
}
$schema = $binding->schema();
$fields = $schema->names();
$special = array('status', 'type', 'name', 'slug', 'notes', 'configuration', '_id');
?>
<div class="row">

	<div class="span4">
		<legend><?= Inflector::singularize($human) ?> details</legend>
		<div class="well">
			<?php
			if (in_array('status', $fields)):
				echo $this->form->field('status', array(
					'type' => 'select',
					'class' => 'input-xlarge',
					'data-switch' => 'status',
					'list' => $model::status()
				));
			endif;
			if (in_array('type', $fields)):
				echo $this->form->field('type', array(
					'type' => 'select',
					'class' => 'input-xlarge',
					'data-switch' => 'type',
					'list' => $model::types()
				));
			endif;
			if (in_array('name', $fields)):
				echo $this->form->field('name', array(
					'class' => 'input-xlarge',
				));
			endif;
			if (in_array('slug', $fields)):
				echo $this->form->field('slug', array(
					'class' => 'input-xlarge',
				));
			endif;
			if (in_array('notes', $fields)):
				echo $this->form->field('notes', array(
					'type' => 'textarea',
					'class' => 'input-xlarge',
					'rows' => 3,
				));
			endif;
			if (in_array('configuration', $fields)):
				echo $this->form->field('configuration', array(
					'type' => 'select',
					'class' => 'input-xlarge',
					'data-switch' => 'configuration',
					'list' => Configurations::find('list')
				));
			endif;
			?>
		</div>
	</div>

	<div class="span7">
		<legend><?= Inflector::singularize($human) ?> content</legend>
		<div class="well">
			<?php
			foreach ($fields as $index => $field) {
				if (in_array($field, $special)) {
					continue;
				}
				$type = $schema->type($field);
				switch($type) {
					case 'configuration':
						$options = array(
							'type' => 'select',
							'class' => 'input-xlarge',
							'data-switch' => 'configuration',
							'list' => Configurations::find('list')
						);
						echo $this->form->field($field, $options);
						break;

					case 'ini':
						$options = array(
							'type' => 'textarea',
							'class' => 'input-xxlarge autogrow',
							'rows' => 10,
						);
						echo $this->form->field($field, $options);
						break;

					case 'integer':
						$options = array(
							'class' => 'input-mini numeric',
						);
						echo $this->form->field($field, $options);
						break;
					case 'date':
						$options = array(
							'class' => 'input-xlarge',
						);
						break;
					case 'string':
						$options = array(
							'class' => 'input-xxlarge',
						);
						echo $this->form->field($field, $options);
						break;
				}
			}
			?>
		</div>
	</div>

</div>
