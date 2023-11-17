<?php
	$model = $this->scaffold->model;
	$searchable = '';
	if(isset($model::$_searchable)){
		if(is_array($model::$_searchable)){
			$searchable = ', '.implode(', ', $model::$_searchable);
		}
	}
?>
<?php
if(isset($collections)): ?>
<div class="form-group">
	<div class="input-group">
		<?= $this->form->select('collection', $collections, array(
			'empty' => false,
			'multiple' => false,
			'placeholder' => 'load collection',
			'class' => 'form-control',
			'value' => ($collection) ? $collection : null,
		)); ?>
		<span class="input-group-addon">
			<i class="fa fa-search"></i>
		</span>
	</div>
</div>
<?php endif ?>
<div class="form-group">
	<div class="input-group">
		<?= $this->form->text('query', array(
			'placeholder' => 'Search on name, slug and notes'.$searchable,
			'class' => 'form-control',
			'value' => (!empty($data['query'])) ? $data['query'] : null,
		)); ?>
		<span class="input-group-addon">
			<i class="fa fa-search"></i>
		</span>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-6">

<?= $this->form->select('status', $model::status(), array(
	'empty' => false,
	'multiple' => true,
	'placeholder' => 'filter by status',
	'class' => 'form-control',
	'value' => !empty($conditions['status']) ? $conditions['status'] : null,
)); ?>

	</div>
	<div class="col-md-6">

<?= $this->form->select('type', $model::types(), array(
	'empty' => false,
	'multiple' => true,
	'placeholder' => 'filter by type',
	'class' => 'form-control',
	'value' => !empty($conditions['type']) ? $conditions['type'] : null,
)); ?>

	</div>
</div>
