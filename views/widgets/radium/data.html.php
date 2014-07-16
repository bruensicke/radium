<?php
$data = (isset($data)) ? $data : array();
?>
<table class="table table-striped table-condensed">
	<colgroup>
		<col width="170" />
		<col width="*" />
	</colgroup>
	<thead>
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	<?php if(empty($data)): ?>
		<tr>
			<td colspan="2"><h5>No data found...</h5></td>
		</tr>
	<?php endif; ?>
	<?php foreach($data as $key => $value): ?>
		<tr data-key="<?= $key ?>" data-value="<?= $value ?>">
			<td class="key"><?= $key ?></td>
			<td class="value"><?= $value ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
