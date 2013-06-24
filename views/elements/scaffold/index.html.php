<?php
$data = array('objects' => $this->scaffold->objects);
echo $this->scaffold->mustache('index', $data += $this->_data);
?>