<?php
use lithium\util\String;
$url = $this->url(array('controller' => $plural));
$content = array();
$content[] = <<<html
		<tr>
			<td colspan="6"><h4>No objects found...</h4></td>
		</tr>
html;

$template = <<<html
		<tr>
			<td>
				<span class="label label_{:status}">{:status}</span>
			</td>
			<td>
				<span class="label label_{:type}">{:type}</span>
			</td>
			<td>
				<a href="$url/view/{:_id}">{:name}</a>
			</td>
			<td data-datetime="{:created}"></td>
			<td data-datetime="{:updated}"></td>
			<td>
				<div class="btn-group">
					<a class="btn btn-mini" href="$url/edit/{:_id}"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="$url/view/{:_id}"><i class="icon-info-sign"></i> View</a></li>
						<li><a href="$url/edit/{:_id}"><i class="icon-pencil"></i> Edit</a></li>
						<li><a href="$url/duplicate/{:_id}"><i class="icon-refresh"></i> Clone</a></li>
						<li class="divider"></li>
						<li><a href="$url/delete/{:_id}"><i class="icon-trash"></i> Delete</a></li>
						<li><a href="$url/remove/{:_id}"><i class="icon-trash"></i> Remove physically</a></li>
						<li><a href="$url/undelete/{:_id}"><i class="icon-retweet"></i> Restore</a></li>
					</ul>
				</div>
			</td>
		</tr>
html;

if (count($$plural) > 0) {
	$content = array();
	foreach ($$plural as $item) {
		$content[] = String::insert($template, $item->data(), array('clean' => true));
	}
}

?>
<table class="table table-striped table-condensed">
	<colgroup>
		<col width="100" />
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
		<?php echo implode("\n", $content); ?>
	</tbody>
</table>
