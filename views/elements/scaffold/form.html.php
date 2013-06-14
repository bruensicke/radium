<?php
$binding = $this->form->binding();
$schema = $binding->schema();
$fields = $schema->names();
$meta = array('status', 'type', 'name', 'slug', 'notes', 'configuration', '_id');
$skip = isset($skip)
	? $skip
	: array();
$readonly = isset($readonly)
	? $readonly
	: array();
?>
<div class="row">

	<div class="span4">
		<legend><?= $this->scaffold->human ?> meta</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta', compact('skip', 'readonly')); ?>
		</div>
	</div>

	<div class="span8">
		<legend><?= $this->scaffold->human ?> details</legend>
		<div class="well">
			<?php $fields = $schema->names(); $skip += $meta; ?>
			<?= $this->scaffold->render('form.fields', compact('fields', 'skip', 'readonly')); ?>
		</div>
	</div>

</div>