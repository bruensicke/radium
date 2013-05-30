<?php
use radium\models\Contents;
use radium\models\Configurations;
use lithium\util\Inflector;

echo $this->scaffold->render('form.config');
$binding = $this->form->binding();
$schema = $binding->schema();
$fields = $schema->names();
$special = array('status', 'type', 'name', 'slug', 'notes', 'configuration', '_id');
?>
<div class="row">

	<div class="span4">
		<legend><?= Inflector::singularize($scaffold['human']) ?> details</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="span8">
		<legend><?= $scaffold['human'] ?> content</legend>
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