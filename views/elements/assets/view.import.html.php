<?= $this->Form->create($object, array('url' => array('library' => 'radium', 'controller' => 'assets', 'action' => 'run', 'id' => $object->id()))); ?>
<div class="row">

	<div class="col-md-8">
		<?php
$data = $object->import(array('dry' => true));
$models = array_keys($data);
foreach ($models as $model) {
	echo sprintf('<h3>%s</h3>', $model);
	echo $this->scaffold->render('data', array('data' => lithium\util\Set::flatten($data[$model])));
}
		?>
	</div>

	<div class="col-md-4">
		<h3>Import control</h3>
		<div class="well">
			<div class="form-group">
			<?= $this->_render('element', 'assets/form.import'); ?>
			<?= $this->Form->submit('Start import', array(
					'class' => 'btn btn-primary',
				));
			?>
		</div>
	</div>

</div>

<?= $this->Form->end(); ?>
<script type="text/javascript">
	$('tr[data-value=valid]').addClass('success');
</script>
