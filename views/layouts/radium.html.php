<!DOCTYPE html>
<html class="<?= \lithium\core\Environment::get(); ?>">
<head>
	<?= $this->_render('element', 'radium/head'); ?>
</head>
<body>
	<div id="container" class="effect mainnav-lg">
		<?= $this->_render('element', 'radium/topnav'); ?>
		
		<div class="boxed">
			<div id="content-container">
				<!-- breadcrumb -->
				<?= $this->_render('element', 'radium/header'); ?>
				<?= $this->flashMessage->render(); ?>
				<div id="page-content">
					<?= $this->content(); ?>
				</div>
			</div>
			
			<?= $this->_render('element', 'radium/sidebar'); ?>
			<?= $this->_render('element', 'radium/aside'); ?>
		</div>
		<?= $this->_render('element', 'radium/footer'); ?>
	</div>
</body>
</html>