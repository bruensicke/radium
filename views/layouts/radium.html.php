<!DOCTYPE html>
<html class="<?= \lithium\core\Environment::get(); ?>">
<head>
	<?= $this->_render('element', 'radium/head'); ?>
</head>
<body class="cover">

<div class="wrapper ">
	<?= $this->_render('element', 'radium/topnav'); ?>
	<div class="body">
		<?= $this->_render('element', 'radium/sidebar'); ?>
		<section class="content">

			<header id="header">
				<?= $this->_render('element', 'radium/header'); ?>
			</header>
			<div id="content">
				<?= $this->content(); ?>
			</div>
			<footer id="footer">
				<?= $this->_render('element', 'radium/footer'); ?>
			</footer>

		</section>
	</div>

</div>

</body>
</html>