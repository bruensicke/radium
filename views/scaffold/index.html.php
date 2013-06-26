<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<ul class="actions pull-right nav nav-pills">
	<li><?= $this->html->link('import', $this->scaffold->action('import'));?></li>
	<li><?= $this->html->link('export', $this->scaffold->action('export'));?></li>
	<li><?= $this->html->link('create', $this->scaffold->action('add'));?></li>
</ul>
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
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<small>See a list of all <?= $this->scaffold->plural ?></small>
	</h1>
</div>

<?= $this->scaffold->render('index'); ?>
