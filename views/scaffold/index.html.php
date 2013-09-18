<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<ul class="actions pull-right nav nav-pills">
	<li><?= $this->html->link('import', $this->scaffold->action('import'), array('icon' => 'upload-alt'));?></li>
	<li><?= $this->html->link('export', $this->scaffold->action('export'), array('icon' => 'download-alt'));?></li>
	<li><?= $this->html->link('create', $this->scaffold->action('add'), array('icon' => 'plus-sign-alt'));?></li>
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
	<li class="active">
		<?= $this->title($this->scaffold->human); ?>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<small>See a list of all <?= $this->scaffold->plural ?></small>
	</h1>
</div>

<?= $this->scaffold->render('index'); ?>
