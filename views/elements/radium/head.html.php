<?php echo $this->html->charset();?>
<title><?php echo $this->title(); ?></title>
<?php echo $this->html->style(array(
	'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700',
	'/radium/css/bootstrap.min',
	'/radium/css/font-awesome.min',
	'/radium/css/select2',
	'/radium/css/select2-bootstrap',
	'/radium/css/ark',
	// '/radium/css/theme',
	'/radium/css/radium',
	// '/li3_bootstrap/css/bootstrap-select.min',
	// '/li3_bootstrap/css/bootstrap-modal',
	// '/li3_bootstrap/css/font-awesome',
	// 'custom',
	// 'app',
	// '/li3_bootstrap/css/bootstrap-datetimepicker.min',
)); ?>
<?php echo $this->html->script(array(
	'/radium/js/jquery.min',
	'/radium/js/bootstrap.min',
	'/radium/js/jquery.autosize.min',
	'/radium/js/jquery.uniform.min.js',
	'/radium/js/select2.min',
	'/radium/js/radium'
)); ?>
<?php echo $this->head(); ?>
<?php echo $this->scripts(); ?>
<?php echo $this->styles(); ?>
<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>

<meta name="MSSmartTagsPreventParsing" content="true" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" href="<?= $this->url('img/apple-touch-icon.png'); ?>">
