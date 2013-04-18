<?php
use lithium\util\Inflector;
use lithium\template\TemplateException;
$title = sprintf('Create %s', Inflector::singularize($human));
$this->title($title);
?>
<?=$this->form->create($$singular, array('class' => 'form')); ?>

<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?=$this->html->link($human, array('action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active"><?=$this->title(); ?></li>
	<li class="pull-right">
		<?=$this->html->link('cancel', array('action' => 'index'));?>
		<?=$this->form->submit('Save', array('class' => 'btn btn-success btn-mini')); ?>
	</li>
</ul>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>Enter your details.</small></h1>
</div>

<?=$this->_render('element', 'forms/errors') ?>
<?php
try {
	echo $this->_render('element', sprintf('%s/form', $plural));
} catch (TemplateException $e) {
	echo $this->_render('element', 'forms/generic', array(), array('library' => 'radium'));
}
?>

<?=$this->form->end(); ?>
