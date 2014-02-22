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
	{{#each data}}
		<tr data-key="{{@key}}" data-value="{{ this }}">
			<td class="key">{{@key}}</td>
			<td class="value">{{ this }}</td>
		</tr>
	{{else}}
		<tr>
			<td colspan="2"><h5>No data found...</h5></td>
		</tr>
	{{/each}}
	</tbody>
</table>
