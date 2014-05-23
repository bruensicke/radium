<?php
$skip = isset($skip)
	? $skip
	: array();
$readonly = isset($readonly)
	? $readonly
	: array();
$fields = array('status', 'type', 'name', 'slug', 'notes', 'config_id', '_id');
echo $this->scaffold->render('form.fields', compact('fields', 'skip', 'readonly'));
