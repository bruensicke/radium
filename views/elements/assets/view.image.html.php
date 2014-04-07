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
<div class="image img_<?= $this->scaffold->type ?>"><img src="<?= $url ?>" class="img-thumbnail" /></div>
