<?php
use lithium\net\http\Router;

$url = Router::match(
	array(
		'library' => 'radium',
		'controller' => 'assets',
		'action' => 'show',
		'id' => $this->scaffold->object->id()),
	$this->request(),
	array('absolute' => true)
);
?>
<div class="plaintext"><pre><?= $url ?></pre></div>
<audio controls><source src="<?= $url ?>" type="<?= $this->scaffold->object['mime']?>"></audio>
<hr />
<?unset($this->scaffold->object['file']);?>
<?= $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($this->scaffold->object->data()))); ?>