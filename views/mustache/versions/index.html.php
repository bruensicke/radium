<table class="table table-striped table-condensed">
	<colgroup>
		<col width="70" />
		<col width="100" />
		<col width="*" />
		<col width="120" />
		<col width="120" />
	</colgroup>
	<thead>
		<tr>
			<th>Status</th>
			<th>Model</th>
			<th>Name</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{{^objects}}
		<tr>
			<td colspan="6"><h4>No objects found...</h4></td>
		</tr>
	{{/objects}}
	{{#objects}}
		<tr{{#deleted}} class="muted"{{/deleted}}>
			<td>
				{{#status}}<span class="label label_{{ status }}">{{ status }}</span>{{/status}}
			</td>
			<td>
				<a href="<?=$this->url(array('action' => 'available')); ?>/{{ model }}">
					{{ model }}
				</a>
			</td>
			<td>
				<a href="<?=$this->url(array('action' => 'view')); ?>/{{ _id }}">
					{{#name}}{{ name }}{{/name}}
					{{^name}}{{ _id }}{{/name}}
				</a>
				{{#notes}}
					<br /><small class="muted">{{ notes }}</small>
				{{/notes}}
			</td>
			<td data-datetime="{{ created }}"></td>
			<td>
				<div class="btn-group">
					<a class="btn btn-mini" href="<?=$this->url(array('action' => 'restore')); ?>/{{ _id }}"><i class="icon-undo"></i> Restore</a>
					<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=$this->url(array('action' => 'view')); ?>/{{ _id }}"><i class="icon-info-sign"></i> View</a></li>
						<li><a href="<?=$this->url(array('action' => 'restore')); ?>/{{ _id }}"><i class="icon-undo"></i> Restore</a></li>
						<li><a href="<?=$this->url(array('action' => 'export')); ?>/{{ _id }}"><i class="icon-download-alt"></i> Export</a></li>
						<li class="divider"></li>
						{{^deleted}}
							<li><a href="<?=$this->url(array('action' => 'delete')); ?>/{{ _id }}"><i class="icon-remove-sign"></i> Delete</a></li>
						{{/deleted}}
						{{#deleted}}
							<li><a href="<?=$this->url(array('action' => 'remove')); ?>/{{ _id }}"><i class="icon-trash"></i> Remove physically</a></li>
							<li><a href="<?=$this->url(array('action' => 'undelete')); ?>/{{ _id }}"><i class="icon-retweet"></i> Restore</a></li>
						{{/deleted}}
					</ul>
				</div>
			</td>
		</tr>
	{{/objects}}
	</tbody>
</table>
