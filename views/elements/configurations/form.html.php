<div class="row">

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="well">
			<?= $this->form->checkbox('visible', array(
				'label' => 'Visible in settings tab?'
			));?>
			<?= $this->form->field('category', array(
				'class' => 'form-control',
				'label' => false
			));?>
		</div>
		<div class="well">
			<?= $this->scaffold->render('form.meta'); ?>
		</div>
	</div>

	<div class="col-md-8">
		<h3><?= $this->scaffold->human ?> details</h3>
		<div class="well">
			<div class="form-group type_boolean">
				<?= $this->form->checkbox('value', array(
					'label' => 'Do you want to enable this setting?'
				));?>
			</div>
			<div class="form-group type_string type_json type_list type_array type_ini type_neon type_navigation">
				<?= $this->form->field('value', array(
					'type' => 'textarea',
					'class' => 'form-control autogrow',
					'style' => 'height: 370px;',
				));?>
			</div>
		</div>
	</div>
</div>
