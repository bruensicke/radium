<?php
$slug = (isset($slug)) ? $slug : '';
$content = $this->Content->get($slug);
return compact('content');
