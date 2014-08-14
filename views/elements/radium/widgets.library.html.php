<div class="panel panel-default col-md-6">
	<div class="panel-heading">All Widgets that you can use </div>
	<div class="panel-body listholder">
		<div id="list1" class="dd">
			<ol class="dd-list">
			{{#each libraries}}
				<li class="dd-item" data-id="{{ @key }}">
					<div class="dd-handle">{{ @key }}</div>
					<ol>
					{{#each @value }}
						foo
					{{/each}}
					</ol>
				</li>
			{{/each}}
			</div>
			</ol>
			<a class="refreshWidget">Refresh List</a>
		</div>
	</div>
</div>
