<?php
$type = (!empty($type))
	? $type
	: 'error';
$title = (!empty($title))
	? $title
	: 'An error occured';
$message = (!empty($message))
	? $message
	: 'An unknown and not further specified error occured. Please check your log-files.';
?>
<div class="alert alert-block alert-<?= $type ?>">
	<h4><?= $title ?></h4>
	<p><?= $message ?></p>
</div>
