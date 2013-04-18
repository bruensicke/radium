<?php
use lithium\core\Libraries;
use lithium\template\TemplateException;
$this->title($human);
$mustache = (bool) Libraries::get('li3_mustache');
?>
<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li class="active"><?=$this->title(); ?></li>
	<li class="pull-right"><?=$this->html->link('create', array('action' => 'add'));?></li>
</ul>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>See a list of all <?= $plural ?></small></h1>
</div>

<?php
try {
	echo ($mustache)
		? $this->mustache->render(sprintf('%s/index', $plural), array($plural => $$plural))
		: $this->_render('element', sprintf('%s/index', $plural));
} catch (TemplateException $e) {
	echo ($mustache)
		? $this->mustache->render('index', array('objects' => array_values($$plural->data())))
		: $this->_render('element', 'scaffold/index', array(), array('library' => 'radium'));
}
?>