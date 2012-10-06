<div class="row">

	<div class="span4">
		<legend>Configuration details</legend>
		<div class="well">
			<?=$this->form->field('type', array(
				'type' => 'select',
				'class' => 'span3',
				'id' => 'type_switch',
				'list' => radium\models\Configurations::types()
			));?>
			<?=$this->form->field('name', array('class' => 'span3', 'required' => true));?>
			<?=$this->form->field('slug', array('class' => 'span3', 'required' => true));?>
			<?=$this->form->field('status', array(
				'type' => 'select',
				'class' => 'span3',
				'list' => radium\models\Configurations::status()
			));?>
			<?=$this->form->field('notes', array('type' => 'textarea', 'label' => 'Notes', 'class' => 'span3'));?>
		</div>
	</div>

	<div class="span7">
		<legend>Configuration value</legend>
		<div class="well">
			<?=$this->form->field('value', array('type' => 'textarea', 'label' => 'Value', 'style' => 'width: 97%; height: 380px;'));?>
			<p class="muted">tip: for boolean values, type <code>0</code> for <code>false</code></p>
		</div>
	</div>

</div>
