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
	{{^data}}
		<tr>
			<td colspan="8"><h2>No data found...</h2></td>
		</tr>
	{{/data}}
	{{#data}}
		<tr>
			<td class="key">{{ key }}</td>
			<td class="value">{{ value }}</td>
		</tr>
	{{/data}}
	</tbody>
</table>
