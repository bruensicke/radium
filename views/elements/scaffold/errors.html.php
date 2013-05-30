<?php if (empty($errors)) return; ?>
<div class="alert alert-block alert-error">
	<h4>Errors!</h4>
	<p>Some errors occured, you should double-check your inputs</p>
	<dl>
		<?php
			foreach ($errors as $field => $_errors) {
				echo sprintf('<dt>%s</dt>', $field);
				foreach ($_errors as $error) {
					echo sprintf('<dd>%s</dd>', $error);
				}
			}
		?>
	</dl>
</div>
