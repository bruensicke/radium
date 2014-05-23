<div class="row">

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="well">
			<div class="form-group">
				<?= $this->scaffold->render('form.meta'); ?>
			</div>
		</div>
	</div>

	<div class="col-md-8">
		<h3><?= $this->scaffold->human ?> body</h3>
		<div class="type_plain">
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
		<div class="type_html">
			<?= $this->form->field('body', array(
				'label' => false,
				'type' => 'textarea',
				'class' => 'rte',
			));?>
		</div>
	</div>

</div>
