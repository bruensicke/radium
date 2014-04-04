<?php
$asset = $this->scaffold->object;
switch($asset->type) {
	case 'plain':
		echo sprintf('<div class="plaintext"><pre>%s</pre></div>', $asset->file->getBytes());
	break;
	case 'import':
		echo $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($asset->decode())));
	break;
	case 'image':
	default:
		#echo $asset->body();
}
?>
<hr />
<?= $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($this->scaffold->object->data()))); ?>