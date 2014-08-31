<?php
/**
 * Copy this file to `app/views/elements/radium/` to customize the output.
 */
?>
<div class="alert alert-<?= !empty($class) ? $class : 'info'; ?> alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<?= $message; ?>
</div>