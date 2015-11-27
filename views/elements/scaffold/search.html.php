<div class="search">
	<a href="#" class="btn btn-search">
		<i class="fa fa-fw fa-filter"></i>
		<?php
		echo sprintf('%s results', (int) $all);
		?>
	</a>
	<div class="search-form">
		<!--<h5>Search <?= $this->scaffold->plural; ?></h5>-->
		<?= $this->form->create(); ?>

		<?= $this->scaffold->render('filter'); ?>

		<div class="clearfix">
			<?= $this->form->submit('Filter results', array('class' => 'btn btn-primary pull-right')); ?>
			<?php
			if (!empty($conditions)):
				echo $this->html->link('reset filter', $this->scaffold->action('index'), array('class' => 'btn btn-default pull-right', 'icon' => 'close'));
			endif;
			?>
		</div>
		<?= $this->form->end(); ?>
	</div>
</div>
