<ul class="breadcrumb">
	<li><?= $this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li class="active"><?= $this->title('radium'); ?></li>
</ul>

<div class="page-header">
	<h1><?= $this->title(); ?> <small>See a list of all components of radium</small></h1>
</div>

<ul class="nav nav-pills">
	<li><?= $this->html->link('Versions', array('library' => 'radium', 'controller' => 'versions', 'action' => 'index')); ?></li>
	<li><?= $this->html->link('Configurations', array('library' => 'radium', 'controller' => 'configurations', 'action' => 'index')); ?></li>
	<li><?= $this->html->link('Contents', array('library' => 'radium', 'controller' => 'contents', 'action' => 'index')); ?></li>
</ul>