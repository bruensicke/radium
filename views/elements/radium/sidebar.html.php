<aside class="sidebar">
<?php
$nav = $this->Navigation->group('sidebar');
if (empty($nav)) {
	$nav = $this->Navigation->render(array(
		array('name' => 'Contents', 'icon' => 'file-text'),
		array('name' => 'Configurations', 'icon' => 'cogs'),
	));
}
echo $nav;
?>
</aside>