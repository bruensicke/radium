<ul class="breadcrumb">
	<li><?=$this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?=$this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<li><?=$this->html->link('Configurations', array('library' => 'radium', 'action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active">
		<?=$this->title(sprintf('Configuration: %s', $configuration->name)); ?>
		<span class="label label_<?= $configuration->status ?>"><?= $configuration->status ?></span>
	</li>
	<li class="pull-right">
		<?=$this->html->link('edit', array('library' => 'radium', 'action' => 'edit', 'args' => array('id' => (string) $configuration->_id)));?>
	</li>
</ul>

<div class="page-header">
	<h1><?=$this->title(); ?> <small>See configuration details.</small></h1>
</div>

<?php
switch($configuration->type) {
	case 'array':
		echo $this->mustache->render('data', array('data' => $configuration->flat()));
		break;
	case 'string':
		echo '<p class="well">'.$configuration->value.'</p>';
		break;
	case 'boolean':
		$val = $configuration->val();
		$label = ($val) ? 'true' : 'false';
		echo '<span class="label label_'.$label.'">'.$label.'</span>';
		break;
}
?>
