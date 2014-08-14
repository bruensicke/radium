<div class="col-md-6 form-group">
	<div class="panel panel-default">
		<div class="panel-heading">Widgets On This Page</div>
		<div class="panel-body">
			<div id="{{ name }}" class="dd">
			<ol class="dd-list">
				{{#each widgets }}
					{{{ widget }}}
				{{/each}}
			</ol>
		</div>
	</div>
</div>
