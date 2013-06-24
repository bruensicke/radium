<?php
$binding = $this->form->binding();
$schema = $binding->schema();
$fields = $schema->names();
$meta = array('status', 'type', 'name', 'slug', 'notes', 'configuration', '_id');
$skip = isset($skip)
	? $skip
	: array();
$readonly = isset($readonly)
	? $readonly
	: array();
?>
<div class="row">

	<div class="span8">
		<div id="uploader"></div>
		<div class="well">
			<div id="uploadResult"></div>
		</div>
	</div>

	<div class="span4">
		<div class="well">
			<?php #echo $this->form->file('import'); ?>
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
	uploader.api = "<?php echo $this->	url($this->scaffold->action('import')); ?>";
	uploader.init();
});
</script>
