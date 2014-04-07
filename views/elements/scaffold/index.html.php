<table class="table table-striped table-condensed table-hover">
	<colgroup>
		<col width="70" />
		<col width="100" />
		<col width="120" />
		<col width="*" />
		<col width="120" />
		<col width="120" />
		<col width="120" />
	</colgroup>
	<thead>
		<tr>
			<th>Status</th>
			<th>Type</th>
			<th>Slug</th>
			<th>Name</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{{#unless objects}}
		<tr>
			<td colspan="7"><h4>No objects found...</h4></td>
		</tr>
	{{/unless}}
	{{#each objects}}
		<tr{{#if deleted}} class="danger"{{/if}}>
			<td>
				{{#if status}}<span class="label label-primary label-{{ status }}">{{ status }}</span>{{/if}}
			</td>
			<td>
				{{#if type}}<span class="label label-primary label-{{ type }}">{{ type }}</span>{{/if}}
			</td>
			<td>
				{{ slug }}
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
			<td data-datetime="{{ created.sec }}">{{ created.sec }}</td>
			<td data-datetime="{{ updated.sec }}">{{ updated.sec }}</td>
			<td>
				<div class="btn-group">
					<a class="btn btn-primary btn-sm" href="{{ scaffold base }}/edit/{{ _id }}"><i class="fa fa-fw fa-pencil"></i> Edit</a>
					<a class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuContext">
						<li role="presentation" class="dropdown-header">Actions</li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/view/{{ _id }}"><i class="fa fa-fw fa-eye"></i> View</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/edit/{{ _id }}"><i class="fa fa-fw fa-pencil2"></i> Edit</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/duplicate/{{ _id }}"><i class="fa fa-fw fa-copy"></i> Clone</a></li>
						<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/export/{{ _id }}"><i class="fa fa-fw fa-upload"></i> Export</a></li>
						<li role="presentation" class="divider"></li>
						{{#if deleted}}
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/undelete/{{ _id }}"><i class="fa fa-fw fa-spinner7 fa-flip-horizontal"></i> Restore</a></li>
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/remove/{{ _id }}"><i class="fa fa-fw fa-remove"></i> Remove physically</a></li>
						{{else}}
							<li role="presentation"><a role="menuitem" href="{{ scaffold base }}/delete/{{ _id }}"><i class="fa fa-fw fa-remove"></i> Delete</a></li>
						{{/if}}
					</ul>
				</div>
			</td>
		</tr>
	{{/each}}
	</tbody>
</table>
