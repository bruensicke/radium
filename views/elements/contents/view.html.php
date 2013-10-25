<?php
$content = $this->scaffold->object;
switch($content->type) {
	case 'plain':
		echo sprintf('<div class="plaintext"><pre>%s</pre></div>', $content->body());
	break;
	case 'markdown':
		echo sprintf('<div class="markdown">%s</div>', $content->body());
	break;
	case 'mustache':
		echo $content->body($this->_data);
	break;
	case 'html':
	default:
		echo $content->body();
}
?>