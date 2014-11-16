<h4>{{ connection.name }}</h4>
<table class="table table-striped table-condensed">
	<colgroup>
		<col width="200" />
		<col width="*" />
	</colgroup>
	<thead>
		<tr>
			<th>Name</th>
			<th>Type</th>
		</tr>
	</thead>
	<tbody>
	{{#each connection}}
		<tr data-key="{{@key}}" data-value="{{ this }}">
			<td class="key">{{@key}}</td>
			<td class="value">{{ this }}</td>
		</tr>
	{{else}}
		<tr>
			<td colspan="3"><h5>No connection parameters found...</h5></td>
		</tr>
	{{/each}}
	</tbody>
</table>
