<table class="table table-striped table-condensed">
	<colgroup>
		<col width="70" />
		<col width="100" />
		<col width="*" />
		<col width="120" />
		<col width="120" />
		<col width="120" />
	</colgroup>
	<thead>
		<tr>
			<th>Status</th>
			<th>Type</th>
			<th>Name</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{{#unless objects}}
		<tr>
			<td colspan="6"><h4>No objects found...</h4></td>
		</tr>
	{{/unless}}
	{{#each objects}}
		<tr{{#if deleted}} class="muted"{{/if}}>
			<td>
				{{#if status}}<span class="label label-primary label-{{ status }}">{{ status }}</span>{{/if}}
			</td>
			<td>
				{{#if type}}<span class="label label-primary label-{{ type }}">{{ type }}</span>{{/if}}
			</td>
			<td>
				<a href="{{ scaffold base }}/view/{{ _id }}">
					{{#if name}}
						{{ name }}
					{{else}}
						{{ _id }}
					{{/if}}
				</a>
				{{#if notes}}
					<br /><small class="muted">{{ notes }}</small>
				{{/if}}
			</td>
			<td data-datetime="{{ created }}"></td>
			<td data-datetime="{{ updated }}"></td>
			<td>
				<div class="btn-group">
					<a class="btn btn-primary btn-sm" href="{{ scaffold base }}/edit/{{ _id }}"><i class="fa fa-fw fa-pencil"></i> Edit</a>
					<a class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuContext">
						<li role="presentation" class="dropdown-header">Actions</li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/view/{{ _id }}"><i class="fa fa-fw fa-eye"></i> View</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/edit/{{ _id }}"><i class="fa fa-fw fa-pencil"></i> Edit</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/duplicate/{{ _id }}"><i class="fa fa-fw fa-copy"></i> Clone</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/export/{{ _id }}"><i class="fa fa-fw fa-cloud-download"></i> Export</a></li>
						<li role="presentation" class="divider"></li>
						{{#if deleted}}
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/undelete/{{ _id }}"><i class="fa fa-fw fa-reply"></i> Restore</a></li>
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/remove/{{ _id }}"><i class="fa fa-fw fa-trash-o"></i> Remove physically</a></li>
						{{else}}
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/delete/{{ _id }}"><i class="fa fa-fw fa-trash-o"></i> Delete</a></li>
						{{/if}}
					</ul>
				</div>
			</td>
		</tr>
	{{/each}}
	</tbody>
</table>
