<?php foreach($objects as $id => $object): ?>
    <item>
		<?php
		foreach($object->rssItem() as $field => $value) {
			echo sprintf('<%s>%s</%s>', $field, $value, $field);
		}
		?>
    </item>
<?php endforeach; ?>