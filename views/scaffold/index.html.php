<?php
use \radium\extensions\helper\Order;
if(isset($conditions)){
	Order::conditions($conditions);
}
?>

<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>


<?
if(!isset($disable) || !in_array('actions', $disable)){
	echo $this->scaffold->render('actions');
}
?>

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
	<li class="active">
		<?= $this->title($this->scaffold->human); ?>
	</li>
</ol>

<div class="header">
	<div class="col-md-12">
		<h3 class="header-title"><?=$this->title(); echo (isset($collection)) ? ': '.$collection : '' ?></h3>
		<!-- <p class="header-info">See a list of all <?= $this->scaffold->plural ?></p> -->
	</div>
</div>

<?
if(!isset($disable) || !in_array('search', $disable)){
	echo $this->scaffold->render('search');
}
?>

<div class="main-content">
	<?= $this->scaffold->render('index'); ?>
</div>
