<ul class="breadcrumb">
	<li><?= $this->html->link('Home', '/');?> <span class="divider">/</span></li>
	<li><?= $this->html->link('radium', '/radium');?> <span class="divider">/</span></li>
	<li><?= $this->html->link('Configurations', array('library' => 'radium', 'action' => 'index'));?> <span class="divider">/</span></li>
	<li class="active">
		<?= $this->title(sprintf('Configuration: %s', $configuration->name)); ?>
		<span class="label label_<?= $configuration->status ?>"><?= $configuration->status ?></span>
		<span class="label label_<?= $configuration->type ?>"><?= $configuration->type ?></span>
	</li>
	<li class="pull-right">
		<?= $this->html->link('edit', array('library' => 'radium', 'action' => 'edit', 'args' => array('id' => (string) $configuration->_id)));?>
	</li>
</ul>

<div class="page-header">
	<h1><?= $this->title(); ?> <small><?= $configuration->notes ?></small></h1>
</div>

<?php
switch($configuration->type) {
	case 'ini':
	case 'neon':
	case 'array':
		echo $this->mustache->render('data', array('data' => $this->mustache->data($configuration->val())));
		break;
	case 'list':
		echo '<div class="well">';
		echo $this->mustache->render('list', array('data' => $configuration->val()));
		echo '</div>';
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
