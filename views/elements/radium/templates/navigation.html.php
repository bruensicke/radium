<ul class="list-group"{{#if id}} id="{{ id }}"{{/if}}>
	{{#if divider}}
		<li class="list-divider"></li>
	{{/if}}
	{{#if caption}}
		<li class="list-header">{{ caption }}</li>
	{{/if}}
	{{#each items}}
		{{#if children}}
			<li class="menu{{#if active}} active{{/if}}">
				<a href="#" class="menu-toggle">
					{{#if icon}}
						<i class="fa fa-{{ icon }} fa-fw"></i>
					{{/if}}
					<span class="menu-title">
						{{ name }}
						{{#with badge}}
							<span class="pull-right badge {{#if color}}badge-{{ color }}{{/if}}">{{ value }}</span>
						{{/with}}
						{{#with label}}
							<span class="pull-right label {{#if color}}label-{{ color }}{{/if}}">{{ value }}</span>
						{{/with}}
					</span>
					<i class="arrow"></i>
				</a>
				<ul class="collapse">
					{{#each children}}
					<li{{#if active}} class="active"{{/if}}>
						<a href="{{ link }}">
							{{#if icon}}
								<i class="{{ icon }}"></i>
							{{/if}}
							{{ name }}
							{{#with badge}}
								<span class="badge {{#if color}}badge-{{ color }}{{/if}} {{ shape }}">{{ value }}</span>
							{{/with}}
						</a>
					</li>
					{{/each}}
				</ul>
			</li>
		{{else}}
			<li{{#if active}} class="active-link"{{/if}}>
				<a href="{{ link }}">
					{{#if icon}}
						<i class="{{ icon }}"></i>
					{{/if}}
					<span class="menu-title">
						{{ name }}
						{{#with badge}}
							<span class="pull-right badge {{#if color}}badge-{{ color }}{{/if}}">{{ value }}</span>
						{{/with}}
						{{#with label}}
							<span class="pull-right label {{#if color}}label-{{ color }}{{/if}}">{{ value }}</span>
						{{/with}}
					</span>
				</a>
			</li>
		{{/if}}
	{{/each}}
</ul>
