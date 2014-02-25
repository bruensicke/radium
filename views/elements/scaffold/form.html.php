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

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="form-group">
			<?= $this->scaffold->render('form.meta', compact('skip', 'readonly')); ?>
		</div>
	</div>

	<div class="col-md-8">
		<h3><?= $this->scaffold->human ?> details</h3>
		<div class="form-group">
			<?php $fields = $schema->names(); $skip += $meta; ?>
			<?= $this->scaffold->render('form.fields', compact('fields', 'skip', 'readonly')); ?>
		</div>
	</div>

</div>