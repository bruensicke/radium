<?php
$this->form->config(array(
	'error' => array('class' => 'help-inline'),
	'templates' => array(
		'field' => '<div class="control-group">{:label}<div class="input controls">{:input}{:error}</div></div>',
		'label' => '<label class="control-label" for="{:id}"{:options}>{:title}</label>',
		'select' => '<select name="{:name}"{:options}>{:raw}</select>',
		'error' => '<span{:options}>{:content}</span>',
)));
?>