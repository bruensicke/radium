<?= $this->page->body; ?>
<?= $this->widget->render(); ?>
<div class="row">
	<div class="template_half left col-md-6">
		<?= $this->widget->target('left'); ?>
	</div>
	<div class="template_half right col-md-6">
		<?= $this->widget->target('right'); ?>
	</div>
</div>