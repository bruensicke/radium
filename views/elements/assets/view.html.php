<?php
$asset = $this->scaffold->object;
if (in_array($asset->type, array('import', 'plain', 'image'))) {
	echo $this->_render('element', sprintf('assets/view.%s', $asset->type));
} else {
	echo $this->_render('element', 'assets/view.default');
}
unset($this->scaffold->object['file']); // no need to show data from grid.fs
?>
<hr />
<?= $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($this->scaffold->object->data()))); ?>