<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<ul class="actions pull-right nav nav-pills">
	<li><?= $this->html->link('cancel', $this->scaffold->action('index'));?></li>
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
		<small>Import <?= $this->scaffold->human ?> files</small>
	</h1>
</div>

<?= $this->scaffold->render('import'); ?>

