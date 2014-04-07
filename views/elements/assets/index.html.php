<div id="uploader"></div>
<div id="uploadResult"></div>

<?= $this->_render('element', 'scaffold/index'); ?>

<script type="text/javascript" charset="utf-8">
uploader.api = "<?php echo $this->url($this->scaffold->action('upload')); ?>";
uploader.template = '<?php echo implode(explode("\n", $this->_render('element', 'import'))); ?>';
uploader.button = '<div><i class="fa fa-2x fa-download fa-fw"></i> UPLOAD</div>';
uploader.init();
</script>
