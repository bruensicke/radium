<hr />
<?php
unset($this->scaffold->object['file']); // no need to show data from grid.fs
echo $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($this->scaffold->object->data()))); ?>
?>