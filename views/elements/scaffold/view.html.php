<?php
use lithium\util\String;
$url = $this->url(array('controller' => $scaffold['plural']));
$content = array();
$content[] = <<<html
		<tr>
			<td colspan="2"><h5>No data found...</h5></td>
		</tr>
html;

$template = <<<html
		<tr>
			<td class="key" data-key="{:key}">{:key}</td>
			<td class="value" data-value="{:value}">{:value}</td>
		</tr>
html;

$fields = $$scaffold['singular']->data();
if (!empty($fields)) {
	$content = array();
	foreach ($fields as $key => $value) {
		$content[] = String::insert($template, compact('key', 'value'), array('clean' => true));
	}
}
?>
<table class="table table-striped table-condensed">
	<colgroup>
		<col width="140" />
		<col width="*" />
	</colgroup>
	<thead>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
		<?php echo implode("\n", $content); ?>
	</tbody>
</table>
