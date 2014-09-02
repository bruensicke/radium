<?php
foreach ($data as $library => $files) {
	echo "<h3>$library</h3>";
	foreach ($files as $file) {
		echo $this->Form->checkbox($file, array(
			'div' => 'form-control',
			'label' => $file,
		));
		echo '<br />';
	}
}

