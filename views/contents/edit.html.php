<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?=$this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<li><?=$this->html->link('Contents', array('library' => 'radium', 'action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active"><?=$this->title(sprintf('Edit content: %s', $content->name)); ?></li>
	<li class="pull-right"><?=$this->html->link('cancel', array('library' => 'radium', 'action' => 'view', 'args' => array('id' => (string) $content->_id)));?></li>
</ul>

<?=$this->form->create($content, array('class' => 'form')); ?>
<?=$this->_render('element', 'forms/config') ?>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>Enter your details.</small></h1>
</div>

<?=$this->_render('element', 'forms/content') ?>

<div class="form-actions">
	<?=$this->form->submit('Save', array('class' => 'btn btn-primary')); ?>
</div>

<?=$this->form->end(); ?>
