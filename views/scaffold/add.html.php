<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>
<?= $this->form->create($this->scaffold->object, array('class' => 'form')); ?>

<ul class="actions pull-right nav nav-pills">
	<li><?= $this->html->link('cancel', $this->scaffold->action('index'));?></li>
	<li><?= $this->form->button('Save', array('type' => 'submit', 'class' => 'btn btn-info btn-sm', 'icon' => 'save')); ?></li>
</ul>
<ul class="breadcrumb">
	<li>
		<?= $this->html->link('Home', '/');?>
	</li>
	<?php if ($scaffold['library'] === 'radium'): ?>
		<li>
			<?= $this->html->link('radium', '/radium');?>
		</li>
	<?php endif; ?>
	<li>
		<?= $this->html->link($this->scaffold->human, array('action' => 'index'));?>
	</li>
	<li class="active">
		<?= $this->title(sprintf('Create: %s', $this->scaffold->human)); ?>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<small>Enter your details.</small>
	</h1>
</div>

<?= $this->scaffold->render('errors'); ?>
<?= $this->scaffold->render('form'); ?>

<?= $this->form->end(); ?>
