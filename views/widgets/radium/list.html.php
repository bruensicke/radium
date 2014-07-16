<?php
$data = (isset($data)) ? $data : array();
?>
<ul>
<?php if(empty($data)): ?>
	<li><h5>No data found...</h5></li>
<?php endif; ?>
<?php foreach($data as $key => $value): ?>
	<?php
	$muted = (substr($value, 0, 2) == '//' || substr($value, 0, 2) == '/*') ? ' class=text-muted' : '';
	?>
	<li<?= $muted ?>><?= $value; ?></li>
<?php endforeach; ?>
</ul>