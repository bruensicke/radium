<?php
$data = $this->scaffold->data($this->scaffold->object->data());
echo $this->scaffold->mustache('view', compact('data'));
?>