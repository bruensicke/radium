<div class="row">

	<div class="span4">
		<legend><?= $this->scaffold->human ?> meta</legend>
		<div class="well">
			<p>Visibility in settings page</p>
			<div class="input-prepend">
				<span class="add-on"><?= $this->form->checkbox('visible');?></span>
				<?= $this->form->text('category', array('class' => 'span2'));?>
			</div>
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="span8">
		<legend><?= $this->scaffold->human ?> details</legend>
		<div class="well type_boolean">
			<p class="muted">Do you want to enable this setting?</p>
			<div class="input-prepend input-append">
				<span class="add-on">Enabled</span>
				<span class="add-on"><?= $this->form->checkbox('value');?></span>
			</div>
		</div>
		<div class="well type_string type_list type_array type_ini type_neon">
			<?= $this->form->field('value', array(
				'type' => 'textarea',
				'class' => 'input-block-level autogrow',
				'style' => 'height: 370px;',
			));?>
		</div>
	</div>

</div>
