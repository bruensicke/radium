<?= $this->scaffold->render('form.config'); ?>
<div class="row">

	<div class="span4">
		<legend>Configuration details</legend>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="span8">
		<legend>Configuration value</legend>
		<div class="well">
			<?= $this->form->field('value', array('type' => 'textarea', 'label' => 'Value', 'style' => 'width: 97%; height: 380px;'));?>
			<p class="muted">tip: for boolean values, type <code>0</code> for <code>false</code></p>
		</div>
	</div>

</div>
