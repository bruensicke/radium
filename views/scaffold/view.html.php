<?php if (!$this->_request->is('ajax')): ?>

<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<?= $this->scaffold->render('actions'); ?>

<ol class="breadcrumb">
	<li>
		<i class="fa fa-home fa-fw"></i>
		<?= $this->html->link('Home', '/');?>
	</li>
	<?php if ($this->scaffold->library === 'radium'): ?>
		<li>
			<?= $this->html->link('radium', '/radium');?>
		</li>
	<?php endif; ?>
	<li>
		<?= $this->html->link($this->scaffold->human, array('action' => 'index'));?>
	</li>
	<li class="active">
		<?= $this->title($this->scaffold->object->title()); ?>
		<?php if (isset($this->scaffold->object->status)): ?>
			<span class="label label-<?= $this->scaffold->object->status ?>"><?= $this->scaffold->object->status ?></span>
		<?php endif; ?>
	</li>
</ol>

<?php endif; ?>

<div class="header">
	<div class="col-md-12">
		<h3 class="header-title"><?= $this->title(); ?></h3>
		<?php if (!empty($this->scaffold->object->notes)): ?>
			<p class="header-info">
				<?= $this->scaffold->object->notes ?>
			</p>
		<?php endif; ?>
	</div>
</div>

<div class="main-content">
	<?= $this->scaffold->render('view'); ?>
</div>

