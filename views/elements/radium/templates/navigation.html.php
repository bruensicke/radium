<ul class="nav nav-stacked">
	{{#if caption}}
		<li class="nav-header"><h4>{{ caption }}</h4></li>
	{{/if}}
	{{#each items}}
		{{#if children}}
			<li class="menu">
				<a href="#" class="menu-toggle">
					{{#if icon}}
						<i class="fa fa-{{ icon }} fa-fw"></i>
					{{/if}}
					{{ name }}
					{{#with badge}}
						<span class="badge {{#if color}}badge-{{ color }}{{/if}} {{ shape }}">{{ value }}</span>
					{{/with}}
					<i class="caret"></i>
				</a>
				<ul class="submenu">
					{{#each children}}
					<li>
						<a href="{{ link }}">
							{{#if icon}}
								<i class="fa fa-{{ icon }} fa-fw"></i>
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
			<li>
				<a href="{{ link }}">
					{{#if icon}}
						<i class="fa fa-{{ icon }} fa-fw"></i>
					{{/if}}
					{{ name }}
					{{#with badge}}
					<span class="badge {{#if color}}badge-{{ color }}{{/if}} {{ shape }}">{{ value }}</span>
					{{/with}}
				</a>
			</li>
		{{/if}}
	{{/each}}
</ul>
