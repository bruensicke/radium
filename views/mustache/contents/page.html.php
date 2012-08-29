{{^page}}
	<h2>no page, yet</h2>
{{/page}}
{{#page}}
	<div class="page" data-id="{{ _id }}">
		<h2>{{ title }}</h2>
		<p>{{ body }}</p>
	</div>
{{/page}}
