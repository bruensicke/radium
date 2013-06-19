<ul class="breadcrumb">
	<li><?= $this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?= $this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<li><?= $this->html->link('Contents', array('library' => 'radium', 'action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active">
		<?= $this->title(sprintf('Contents: %s', $content->name)); ?>
		<span class="label label_<?= $content->status ?>"><?= $content->status ?></span>
	</li>
	<li class="pull-right">
		<?= $this->html->link('edit', array('library' => 'radium', 'action' => 'edit', 'args' => array('id' => (string) $content->_id)));?>
	</li>
</ul>

<div class="page-header">
	<h1><?= $this->title(); ?> <small>See content details.</small></h1>
</div>
<p><?= $content->notes ?></p>

<?php
switch($content->type) {
	case 'post':
	case 'page':
	default:
		echo $content->body;
}
?>
