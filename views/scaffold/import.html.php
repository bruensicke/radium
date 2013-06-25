<?php # $this->form->create($this->scaffold->object, array('class' => 'form', 'type' => 'file')); ?>

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
	<li class="active">
		<?= $this->title($this->scaffold->human); ?>
	</li>
	<li class="pull-right">
		<ul class="actions">
			<li><?= $this->html->link('cancel', $this->scaffold->action('index'));?></li>
		</ul>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<small>Import <?= $this->scaffold->human ?> files</small>
	</h1>
</div>

<?= $this->scaffold->render('import'); ?>

