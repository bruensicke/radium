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
			<td colspan="2"><h5>No data found...</h5></td>
		</tr>
	{{/data}}
	{{#data}}
		<tr>
			<td class="key" data-key="{{ key }}">{{ key }}</td>
			<td class="value" data-value="{{ value }}">{{ value }}</td>
		</tr>
	{{/data}}
	</tbody>
</table>
