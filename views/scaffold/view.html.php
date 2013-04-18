<?php
use lithium\util\Inflector;
use lithium\core\Libraries;
use lithium\template\TemplateException;
$this->title($$singular->title());
$mustache = (bool) Libraries::get('li3_mustache');
?>
<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?=$this->html->link($human, array('action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active">
		<?=$this->title(); ?>
		<?php if (!empty($$singular->status)): ?>
			<span class="label label_<?= $$singular->status ?>"><?= $$singular->status ?></span>
		<?php endif; ?>
	</li>
	<li class="pull-right">
		<?=$this->html->link('edit', array('action' => 'edit', 'args' => array('id' => (string) $$singular->_id)));?>
	</li>
</ul>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>View details.</small></h1>
</div>

<?php
try {
	$template = sprintf('%s/view', $plural);
	echo ($mustache)
		? $this->mustache->render($template, array($singular => $$singular))
		: $this->_render('element', $template);
} catch (TemplateException $e) {
	echo ($mustache)
		? $this->mustache->render('data', array('data' => $this->mustache->data($$singular->data())))
		: $this->_render('element', 'scaffold/view', array(), array('library' => 'radium'));
}
?>
