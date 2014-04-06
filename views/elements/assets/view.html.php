<?php
use lithium\net\http\Router;

$asset = $this->scaffold->object;
switch($asset->type) {
	case 'plain':
		echo sprintf('<div class="plaintext"><pre>%s</pre></div>', $asset->file->getBytes());
	break;
	case 'import':
		echo $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($asset->decode())));
	break;
	case 'image':
		$url = Router::match(
			array(
				'library' => 'radium',
				'controller' => 'assets',
				'action' => 'show',
				'id' => $asset->id()),
			$this->request(),
			array('absolute' => true)
		);
		echo sprintf('<div class="plaintext"><pre>%s</pre></div>', $url);
		echo sprintf('<div class="image img_%s"><img src="%s" class="img-thumbnail" /></div>', $asset->extension, $url);
	default:
		#echo $asset->body();
}
?>
<hr />
<?= $this->scaffold->render('data', array('data' => \lithium\util\Set::flatten($this->scaffold->object->data()))); ?>