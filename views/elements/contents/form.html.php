<?= $this->scaffold->render('form.config'); ?>
<div class="row">

	<div class="span4">
		<legend>Contents details</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="span8">
		<legend>Contents body</legend>
		<div class="well">
			<?= $this->form->field('body', array('type' => 'textarea', 'label' => 'Body', 'style' => 'width: 97%; height: 380px;'));?>
		</div>
	</div>

</div>
