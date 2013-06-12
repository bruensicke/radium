<div class="tabbable">
	<div class="breadcrumb">
		<div id="tab" class="btn-group" data-toggle="buttons-radio">
			{{#filter}}
				<a href="<?= $this->url(); ?>{{ key }}:{{ key }}" class="btn" data-toggle="tab">{{ value }}</a>
			{{/filter}}
		</div>
	</div>
	<div id="result"></div>
</div>
