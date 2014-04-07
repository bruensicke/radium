<ul>
{{#each data}}
	<li{{ muted_if_comment this }}>{{ this }}</li>
{{else}}
	<li><h5>No data found...</h5></li>
{{/each}}
</ul>