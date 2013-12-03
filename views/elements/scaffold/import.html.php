<div class="row">

	<div class="span8">
		<div id="uploader"></div>
		<div id="uploadResult"></div>
	</div>

	<div class="span4">
		<div class="well">
			<h3>Data import</h3>
			<p>Select and upload files that you exported.</p>

			<ul>
				<?php foreach($exports as $export): ?>
					<li><?= $this->html->link($export, array('action' => 'import', 'args' => array($export)), array('class' => 'directimport')); ?></li>
				<?php endforeach;?>
			</ul>

		</div>
	</div>

</div>
<?=$this->html->style('/radium/css/import'); ?>
<script type="text/javascript" charset="utf-8">
head.js(
	{ fineuploader: "<?php echo $this->path('/li3_bootstrap/js/fineuploader.min.js'); ?>"},
	{ uploader: "<?php echo $this->path('/radium/js/import.js'); ?>"}
);
head.ready(function() {
	uploader.api = "<?php echo $this->url($this->scaffold->action('import')); ?>";
	uploader.template = '<?php echo implode(explode("\n", $this->_render('element', 'import'))); ?>';
	uploader.button = '<div><i class="icon-upload-alt icon-white"></i> import files</div>';
	uploader.init();
});
</script>
