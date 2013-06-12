<?= $this->form->create($$scaffold['singular'], array('class' => 'form')); ?>

<ul class="breadcrumb">
	<li><?= $this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<?php if ($scaffold['library'] === 'radium'): ?>
		<li><?= $this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<?php endif; ?>
	<li><?= $this->html->link($scaffold['human'], array('action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active"><?= $this->title($$scaffold['singular']->title()); ?></li>
	<li class="pull-right">
		<?= $this->html->link('cancel', $this->scaffold->action('view'));?>
		<?= $this->form->submit('Save', array('class' => 'btn btn-success btn-mini')); ?>
	</li>
</ul>

<div class="page-header">
	<h1><?= $this->title(); ?> <small>Enter your details.</small></h1>
</div>

<?= $this->scaffold->render('errors'); ?>
<?= $this->scaffold->render('form'); ?>

<?= $this->form->end(); ?>