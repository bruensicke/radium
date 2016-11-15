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
						'id' => 'content-body',
						'class' => 'form-control',
						'style' => 'height: 370px;',
					));?>
				</div>
			</div>
		</div>
		<div class="type_html type_handlebars">
			<div class="form-control rte" data-for="#content-body"><?php echo $object->body; ?></div>
		</div>
	</div>

</div>
