{{^news}}
	<h2>no news, yet</h2>
{{/news}}
{{#news}}
	<div class="news" data-id="{{ _id }}">
		<h2>{{ headline }}</h2>
		<p>{{ body }}</p>
	</div>
{{/news}}
