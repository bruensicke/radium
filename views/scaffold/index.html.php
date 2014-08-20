<?= $this->html->style('/radium/css/scaffold', array('inline' => false)); ?>

<div class="actions pull-right btn-group">
	<?= $this->html->link('import', $this->scaffold->action('import'), array('class' => 'btn btn-default', 'icon' => 'download'));?>
	<?= $this->html->link('export', $this->scaffold->action('export'), array('class' => 'btn btn-default', 'icon' => 'upload'));?>
	<?= $this->html->link('create', $this->scaffold->action('add'), array('class' => 'btn btn-primary', 'icon' => 'plus'));?>
</div>

<ol class="breadcrumb">
	<li>
		<i class="fa fa-home fa-fw"></i>
		<?= $this->html->link('Home', '/');?>
	</li>
	<?php if ($this->scaffold->library === 'radium'): ?>
		<li>
			<?= $this->html->link('radium', '/radium');?>
		</li>
	<?php endif; ?>
	<li class="active">
		<?= $this->title($this->scaffold->human); ?>
	</li>
</ol>

<div class="header">
	<div class="col-md-12">
		<h3 class="header-title"><?= $this->title(); ?></h3>
		<!-- <p class="header-info">See a list of all <?= $this->scaffold->plural ?></p> -->
	</div>
</div>

<div class="main-content">
	<?= $this->scaffold->render('index'); ?>
</div>

<?php
echo $this->html->style(array(
	'/radium/css/datatable',
));
echo $this->html->script(array(
	// '/radium/js/jquery.dataTables.min',
	'//cdn.datatables.net/1.10.1/js/jquery.dataTables.js',
	'//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js',
	// '/radium/js/DT_bootstrap.min',
));
?>
<script type="text/javascript">
$().ready(function() {
    $('.main-content .table').dataTable({
    	searching: true,
    	stateSave: true,
    	scrollX: true,
    	deferRender: true,
    	pageLength: 100,
    	lengthMenu: [ [100, 250, 500, -1], [100, 250, 500, "All"] ],
        ajax: {
        	url: 'api/<?= $this->scaffold->controller ?>',
        	dataSrc: 'objects'
        },
		// columnDefs: [
		//  	{ "visible": false, "targets": 0 },
		//     {
		//       "data": null,
		//       "defaultContent": "content",
		//       "targets": -1
		//     },
		// ],
        // see http://datatables.net/reference/option/columns
        columns: [
        	<?php
        	$result = array();
        	foreach($fields as $field => $options) {
        		$params = array(
        			'data' => $field,
        			// 'options' => $options,
        			'defaultContent' => 'n/a',
        		);
        		if ($field == '_id') {
        			$params['visible'] = false;
        		}
        		$result[] = json_encode($params);
        	}
        	// $result[] = json_encode(array('data' => null, 'defaultContent' => 'fobar'));
        	echo implode(',', $result);
        	?>
        ]
    });
});
</script>
