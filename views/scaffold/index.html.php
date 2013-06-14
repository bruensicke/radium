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
		<?= $this->html->link('create', array('action' => 'add'));?>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<small>See a list of all <?= $this->scaffold->plural ?></small>
	</h1>
</div>

<?= $this->scaffold->render('index'); ?>
