<ul class="breadcrumb">
	<li><?= $this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<?php if ($scaffold['library'] === 'radium'): ?>
		<li><?= $this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<?php endif; ?>
	<li><?= $this->html->link($scaffold['human'], array('action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active">
		<?= $this->title($$scaffold['singular']->title()); ?>
		<?php if (isset($$scaffold['singular']->status)): ?>
			<span class="label label_<?= $$scaffold['singular']->status ?>"><?= $$scaffold['singular']->status ?></span>
		<?php endif; ?>
	</li>
	<li class="pull-right">
		<?= $this->html->link('edit', $this->scaffold->action('edit'));?>
	</li>
</ul>

<div class="page-header">
	<h1>
		<?= $this->title(); ?>
		<?php if (isset($$scaffold['singular']->notes)): ?>
			<small><?= $$scaffold['singular']->notes ?></small>
		<?php else: ?>
			<small>View details.</small>
		<?php endif; ?>
	</h1>
</div>

<?= $this->scaffold->render('view'); ?>
