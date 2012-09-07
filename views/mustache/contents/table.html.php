<table class="table table-striped table-condensed">
	<colgroup>
		<col width="70" />
		<col width="100" />
		<col width="180" />
		<col width="*" />
		<col width="120" />
		<col width="180" />
	</colgroup>
	<thead>
		<tr>
			<th>Status</th>
			<th>Type</th>
			<th>Name</th>
			<th>Notes</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{{^contents}}
		<tr>
			<td colspan="8"><h2>No contents found...</h2></td>
		</tr>
	{{/contents}}
	{{#contents}}
		<tr{{#deleted}} class="muted"{{/deleted}}>
			<td><span class="label label_{{ status }}">{{ status }}</span></td>
			<td>
				<a href="<?=$this->url('/'); ?>radium/contents/{{ type }}">
					<span class="label label_{{ type }}">{{ type }}</span>
				</a>
			</td>
			<td><a href="<?=$this->url('/'); ?>radium/contents/view/{{ _id }}">{{ name }}</a></td>
			<td class="muted">{{ notes }}</td>
			<td data-datetime="{{ created }}"></td>
			<td>
				<div class="btn-group">
					<a class="btn btn-small" href="<?=$this->url('/'); ?>radium/contents/edit/{{ _id }}"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=$this->url('/'); ?>radium/contents/view/{{ _id }}"><i class="icon-info-sign"></i> View</a></li>
						<li><a href="<?=$this->url('/'); ?>radium/contents/edit/{{ _id }}"><i class="icon-pencil"></i> Edit</a></li>
						<li><a href="<?=$this->url('/'); ?>radium/contents/duplicate/{{ _id }}"><i class="icon-refresh"></i> Clone</a></li>
						<li class="divider"></li>
						{{^deleted}}
							<li><a href="<?=$this->url('/'); ?>radium/contents/delete/{{ _id }}"><i class="icon-trash"></i> Delete</a></li>
						{{/deleted}}
						{{#deleted}}
							<li><a href="<?=$this->url('/'); ?>radium/contents/remove/{{ _id }}"><i class="icon-trash"></i> Remove physically</a></li>
							<li><a href="<?=$this->url('/'); ?>radium/contents/undelete/{{ _id }}"><i class="icon-retweet"></i> Restore</a></li>
						{{/deleted}}
					</ul>
				</div>
			</td>
		</tr>
	{{/contents}}
	</tbody>
</table>
