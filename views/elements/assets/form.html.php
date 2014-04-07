<?php
$binding = $this->form->binding();
$model = $binding->model();
$tabs = $model::renderLayout();
$schema = $binding->schema();
$fields = $schema->names();
$meta = array('status', 'type', 'name', 'slug', 'notes', 'configuration', '_id');
$skip = isset($skip)
	? $skip
	: array();
$readonly = isset($readonly)
	? $readonly
	: array();
if (!empty($tabs)) {
	echo $this->_render('element', '/scaffold/form.tabs', compact('tabs', 'fields', 'skip', 'readonly'));
	return;
}
?>
<div class="row">

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="well">
			<div class="form-group">
				<?= $this->scaffold->render('form.meta', compact('skip', 'readonly')); ?>
			</div>
		</div>
	</div>

	<div class="col-md-8 type_import">
		<h3>Import control</h3>
		<div class="well">
			<div class="form-group">
				<?= $this->_render('element', 'assets/form.import'); ?>
			</div>
		</div>
	</div>

	<div class="col-md-8 type_default type_plain type_data type_audio type_video type_image">
		<h3><?= $this->scaffold->human ?> details</h3>
		<div class="well">
			<div class="form-group">
				<?php $fields = $schema->names(); $skip += $meta; ?>
				<?= $this->scaffold->render('form.fields', compact('fields', 'skip', 'readonly')); ?>
			</div>
		</div>
	</div>

</div>