<ul class="list-group">
{{#each data}}
	<li{{ muted_if_comment this }} class="list-group-item">{{ this }}</li>
{{else}}
	<li class="list-group-item"><h5>No data found...</h5></li>
{{/each}}
</ul>