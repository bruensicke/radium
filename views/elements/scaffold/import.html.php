<div id="uploader"></div>
<div id="uploadResult"></div>

<script type="text/javascript" charset="utf-8">
uploader.api = "<?php echo $this->url($this->scaffold->action('import')); ?>";
uploader.template = '<?php echo implode(explode("\n", $this->_render('element', 'import'))); ?>';
uploader.button = '<div><i class="fa fa-2x fa-download fa-fw"></i> IMPORT FILES</div>';
uploader.init();
</script>
