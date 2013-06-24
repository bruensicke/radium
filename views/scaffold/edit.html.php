<?= $this->form->create($this->scaffold->object, array('class' => 'form')); ?>

<ul class="breadcrumb">
	<li>
		<?= $this->html->link('Home', '/');?>
		<span class="divider">/</span>
	</li>
	<?php if ($scaffold['library'] === 'radium'): ?>
		<li>
			<?= $this->html->link('radium', '/radium');?>
			<span class="divider">/</span>
		</li>
	<?php endif; ?>
	<li>
		<?= $this->html->link($this->scaffold->human, array('action' => 'index'));?>
		<span class="divider">/</span>
	</li>
	<li class="active">
		<?= $this->title(sprintf('Edit: %s', $this->scaffold->object->title())); ?>
	</li>
	<li class="pull-right">
		<ul class="actions">
			<li><?= $this->html->link('cancel', $this->scaffold->action('view'));?></li>
			<li><?= $this->form->submit('Save', array('class' => 'btn btn-success btn-mini')); ?></li>
		</ul>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<?php if (!empty($this->scaffold->object->notes)): ?>
			<small><?= $this->scaffold->object->notes ?></small>
		<?php else: ?>
			<small>Enter details.</small>
		<?php endif; ?>
	</h1>
</div>

<?= $this->scaffold->render('errors'); ?>
<?= $this->scaffold->render('form'); ?>

<?= $this->form->end(); ?>