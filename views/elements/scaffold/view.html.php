<?php
use lithium\util\String;
$url = $this->url(array('controller' => $plural));
$content = array();
$template = <<<html
		<tr>
			<td class="key">{:key}</td>
			<td class="value">{:value}</td>
		</tr>
html;

$fields = $$singular->data();
foreach ($fields as $key => $value) {
	$content[] = String::insert($template, compact('key', 'value'), array('clean' => true));
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
