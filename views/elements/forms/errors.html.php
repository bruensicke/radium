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
<?php if (empty($errors)) return; ?>
<div class="alert alert-block alert-error">
	<h4>Errors!</h4>
	<p>Some errors occured, you should double-check your inputs</p>
	<dl>
		<?php
			foreach ($errors as $field => $_errors) {
				echo sprintf('<dt>%s</dt>', $field);
				foreach ($_errors as $error) {
					echo sprintf('<dd>%s</dd>', $error);
				}
			}
		?>
	</dl>
</div>
