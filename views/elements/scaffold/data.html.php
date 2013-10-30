<?php
$data = $this->mustache->data($this->scaffold->object->data());
echo $this->scaffold->mustache('view', compact('data'));
?>