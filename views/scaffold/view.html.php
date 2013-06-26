<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<ul class="actions pull-right nav nav-pills">
	<li><?= $this->html->link('export', $this->scaffold->action('export'));?></li>
	<li><?= $this->html->link('delete', $this->scaffold->action('delete'));?></li>
	<li><?= $this->html->link('edit', $this->scaffold->action('edit'));?></li>
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
	<li>
		<?= $this->html->link($this->scaffold->human, array('action' => 'index'));?>
		<span class="divider">/</span>
	</li>
	<li class="active">
		<?= $this->title($this->scaffold->object->title()); ?>
		<?php if (isset($this->scaffold->object->status)): ?>
			<span class="label label_<?= $this->scaffold->object->status ?>"><?= $this->scaffold->object->status ?></span>
		<?php endif; ?>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<?php if (!empty($this->scaffold->object->notes)): ?>
			<small><?= $this->scaffold->object->notes ?></small>
		<?php else: ?>
			<small>View details.</small>
		<?php endif; ?>
	</h1>
</div>

<?= $this->scaffold->render('view'); ?>
