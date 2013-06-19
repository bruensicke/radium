<div class="row">

	<div class="span4">
		<legend><?= $this->scaffold->human ?> meta</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="span8">
		<legend><?= $this->scaffold->human ?> details</legend>
		<div class="well">
			<?= $this->form->field('value', array(
				'type' => 'textarea',
				'class' => 'input-block-level',
				'style' => 'height: 370px;',
			));?>
		</div>
	</div>

</div>
