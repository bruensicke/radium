<div class="row">

	<div class="col-md-6">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="form-group">
			<p>Visibility in settings page</p>
			<div class="input-prepend">
				<span class="add-on"><?= $this->form->checkbox('visible');?></span>
				<?= $this->form->text('category', array('class' => 'span2'));?>
			</div>
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="col-md-6">
		<h3><?= $this->scaffold->human ?> details</h3>
		<div class="form-group type_boolean">
			<p class="muted">Do you want to enable this setting?</p>
			<div class="input-prepend input-append">
				<span class="add-on">Enabled</span>
				<span class="add-on"><?= $this->form->checkbox('value');?></span>
			</div>
		</div>
		<div class="form-group type_string type_json type_list type_array type_ini type_neon">
			<?= $this->form->field('value', array(
				'type' => 'textarea',
				'class' => 'form-control autogrow',
				'style' => 'height: 370px;',
			));?>
		</div>
	</div>

</div>
