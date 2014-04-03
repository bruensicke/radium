<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>
<?= $this->form->create($this->scaffold->object, array('class' => 'form')); ?>

<div class="actions pull-right btn-group">
	<?= $this->html->link('cancel', $this->scaffold->action('view'), array('class' => 'btn btn-default'));?>
	<?= $this->form->button('Save', array('type' => 'submit', 'class' => 'btn btn-primary', 'icon' => 'save')); ?>
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
		<?= $this->title(sprintf('Edit: %s', $this->scaffold->object->title())); ?>
	</li>
</ol>

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
	<?= $this->scaffold->render('errors'); ?>
	<?= $this->scaffold->render('form'); ?>
</div>

<?= $this->form->end(); ?>