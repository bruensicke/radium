<?php
$data = array(
	'objects' => $this->scaffold->objects,
	'scaffold' => $this->scaffold->data(),
);
echo $this->scaffold->mustache('index', $data += $this->_data);
?>