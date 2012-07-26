<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?=$this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<li class="active"><?=$this->title('Configurations'); ?></li>
	<li class="pull-right"><?=$this->html->link('create', array('library' => 'radium', 'action' => 'add'));?></li>
</ul>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>See a list of all configurations</small></h1>
</div>

<?php
if (\lithium\core\Libraries::get('li3_mustache')) {
	echo $this->mustache->render('configurations/table', compact('configurations'));
}

?>