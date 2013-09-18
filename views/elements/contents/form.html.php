<div class="row">

	<div class="col-md-4">
		<legend><?= $this->scaffold->human ?> meta</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="col-md-8">
		<legend><?= $this->scaffold->human ?> details</legend>
		<div class="well">
			<?= $this->form->field('body', array(
				'type' => 'textarea',
				'class' => 'form-control input-block-level',
				'style' => 'height: 370px;',
			));?>
		</div>
	</div>

</div>
