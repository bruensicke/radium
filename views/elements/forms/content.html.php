<div class="row">

	<div class="span4">
		<legend>Content settings</legend>
		<div class="well">
			<?=$this->form->field('type', array(
				'type' => 'select',
				'class' => 'span3',
				'id' => 'type_switch',
				'list' => \radium\models\Contents::types()
			));?>
			<?=$this->form->field('name', array('class' => 'span3'));?>
			<?=$this->form->field('slug', array('class' => 'span3', 'label' => 'slug', 'required' => true));?>
			<?=$this->form->field('category', array('class' => 'span3'));?>
			<?=$this->form->field('status', array(
				'type' => 'select',
				'class' => 'span3',
				'list' => \radium\models\Contents::status()
			));?>
			<?=$this->form->field('notes', array('type' => 'textarea', 'label' => 'Notes', 'class' => 'span3'));?>
		</div>
	</div>

	<div class="span7">
		<legend>Content details</legend>
		<div class="well type_page type_post type_wiki">
			<?=$this->form->field('title', array('class' => 'span6'));?>
			<?=$this->form->field('body', array('type' => 'textarea', 'label' => 'Body', 'style' => 'width: 97%; height: 380px;'));?>
		</div>
		<div class="well type_news type_faq type_term">
			<?=$this->form->field('headline', array('class' => 'span6'));?>
			<?=$this->form->field('body', array('type' => 'textarea', 'label' => 'Body', 'style' => 'width: 97%; height: 380px;'));?>
		</div>
	</div>

</div>
