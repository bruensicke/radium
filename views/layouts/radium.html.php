<!DOCTYPE html>
<html class="<?= \lithium\core\Environment::get(); ?>">
<head>
	<?= $this->_render('element', 'radium/head'); ?>
</head>
<body class="cover">
	<div class="wrapper">
		<div class="body">
			<?= $this->_render('element', 'radium/sidebar'); ?>
			<section class="content">
				<?= $this->_render('element', 'radium/topnav'); ?>
				<header id="header">
					<?= $this->_render('element', 'radium/header'); ?>
				</header>
				<div id="content">
					<?= $this->flashMessage->render(); ?>
					<?= $this->content(); ?>
				</div>
			</section>
		</div>
		<?= $this->_render('element', 'radium/footer'); ?>
	</div>
</body>
</html>