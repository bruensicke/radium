<div class="row">

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="well">
			<?= $this->form->checkbox('accessible', array(
				'label' => 'Accessible via url?'
			));?>
			<?= $this->form->field('layout', array(
				'type' => 'select',
				'class' => 'form-control',
				'label' => false,
				'list' => $this->Configuration->get('contents.templates',
					array('default' => 'Default')
				),
			));?>
		</div>
		<div class="well">
			<div class="form-group">
				<?= $this->scaffold->render('form.meta'); ?>
			</div>
		</div>
	</div>

	<div class="col-md-8">
		<h3><?= $this->scaffold->human ?> body</h3>
		<div class="type_plain type_mustache type_markdown">
			<div class="well">
				<div class="form-group">
					<?= $this->form->field('body', array(
						'type' => 'textarea',
						'class' => 'form-control',
						'style' => 'height: 370px;',
					));?>
				</div>
			</div>
		</div>
		<div class="type_html type_handlebars">
			<?= $this->form->field('body', array(
				'label' => false,
				'type' => 'textarea',
				'class' => 'rte',
			));?>
		</div>
	</div>

</div>
