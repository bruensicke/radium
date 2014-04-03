<?php if (empty($errors)) return; ?>
<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>

	<h4><i class="fa fa-warning2"></i> Warning</h4>
	<p>Some errors occured, you should double-check your inputs</p>
	<dl class="dl-intended">
		<?php
			foreach ($errors as $field => $_errors) {
				echo sprintf('<dt>%s</dt>', \lithium\util\Inflector::humanize($field));
				foreach ($_errors as $error) {
					echo sprintf('<dd>%s</dd>', $error);
				}
			}
		?>
	</dl>
</div>
