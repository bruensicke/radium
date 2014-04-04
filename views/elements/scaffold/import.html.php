<div id="uploader"></div>
<div id="uploadResult"></div>

<script type="text/javascript" charset="utf-8">
uploader.api = "<?php echo $this->url($this->scaffold->action('import')); ?>";
uploader.template = '<?php echo implode(explode("\n", $this->_render('element', 'import'))); ?>';
uploader.button = '<div><i class="fa fa-2x fa-cloud-upload fa-fw"></i> import files</div>';
uploader.init();
</script>
