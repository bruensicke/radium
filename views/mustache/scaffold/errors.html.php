<div class="alert alert-block alert-error">
	<h4>Errors!</h4>
	<p>Some errors occured, you should double-check your inputs</p>
	<dl>
		{{#errors}}
			<dt>{{ key }}</dt>
			{{#value}}
				<dd>{{ . }}</dd>
			{{/value}}
		{{/errors}}
	</dl>
</div>
