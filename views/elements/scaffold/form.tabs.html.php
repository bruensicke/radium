<?php
$nav = $panels = '';
foreach($tabs as $tab => $fields) {
	$slug = strtolower(\lithium\util\Inflector::slug($tab));
	$active = $nav ? '' : ' active' ;
	$nav .= sprintf('<li class="%s"><a href="#%s" data-toggle="tab">%s</a></li>', $active, $slug, $tab);
	$panels .= sprintf('<div class="tab-pane%s" id="%s">', $active, $slug);
	$panels .= $this->scaffold->render('form.fields', compact('fields', 'skip', 'readonly'));
	$panels .= '</div>';
}
?>
<div class="row">

	<div class="col-md-4">
		<h3><?= $this->scaffold->human ?> meta</h3>
		<div class="well">
			<div class="form-group">
				<?= $this->scaffold->render('form.meta', compact('skip', 'readonly')); ?>
			</div>
		</div>
	</div>

	<div class="col-md-8">
		<h3><?= $this->scaffold->human ?> details</h3>
		<ul class="nav nav-tabs">
			<?php echo $nav?>
		</ul>
		<div class="tab-content">
			<?php echo $panels?>
		</div>
	</div>

</div>
