<table class="table table-striped table-condensed">
	<colgroup>
		<col width="70" />
		<col width="100" />
		<col width="*" />
		<col width="120" />
		<col width="120" />
		<col width="180" />
	</colgroup>
	<thead>
		<tr>
			<th>Status</th>
			<th>Type</th>
			<th>Name</th>
			<th>Description</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{{^configurations}}
		<tr>
			<td colspan="8"><h2>No configurations found...</h2></td>
		</tr>
	{{/configurations}}
	{{#configurations}}
		<tr{{#deleted}} class="low"{{/deleted}}>
			<td><span class="label label_{{ status }}">{{ status }}</span></td>
			<td><span class="label label_{{ type }}">{{ type }}</span></td>
			<td><a href="<?=$this->url('/'); ?>radium/configurations/view/{{ _id }}"><small class="low">{{ name }}</small></a></td>
			<td>{{ description }}</td>
			<td data-datetime="{{ created }}"></td>
			<td>
				<div class="btn-group">
					<a class="btn btn" href="<?=$this->url('/'); ?>radium/configurations/edit/{{ _id }}"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=$this->url('/'); ?>radium/configurations/view/{{ _id }}"><i class="icon-info-sign"></i> View</a></li>
						<li><a href="<?=$this->url('/'); ?>radium/configurations/edit/{{ _id }}"><i class="icon-pencil"></i> Edit</a></li>
						<li class="divider"></li>
						{{^deleted}}
							<li><a href="<?=$this->url('/'); ?>radium/configurations/delete/{{ _id }}"><i class="icon-trash"></i> Delete</a></li>
						{{/deleted}}
						{{#deleted}}
							<li><a href="<?=$this->url('/'); ?>radium/configurations/undelete/{{ _id }}"><i class="icon-trash"></i> Restore</a></li>
						{{/deleted}}
					</ul>
				</div>
			</td>
		</tr>
	{{/configurations}}
	</tbody>
</table>
