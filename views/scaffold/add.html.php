<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>
<?= $this->form->create($this->scaffold->object, array('class' => 'form')); ?>

<div class="actions pull-right btn-group">
	<?= $this->html->link('cancel', $this->scaffold->action('index'), array('class' => 'btn btn-default', 'icon' => 'close'));?>
	<?= $this->form->button('save', array('type' => 'submit', 'class' => 'btn btn-primary', 'icon' => 'save')); ?>
</div>

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
		<?= $this->title(sprintf('Create: %s', $this->scaffold->singular)); ?>
	</li>
</ol>

<div class="header">
	<div class="col-md-12">
		<h3 class="header-title"><?= $this->title(); ?></h3>
	</div>
</div>

<div class="main-content">
	<?= $this->scaffold->render('errors'); ?>
	<?= $this->scaffold->render('form'); ?>
</div>

<?= $this->form->end(); ?>
