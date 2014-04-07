<ol class="breadcrumb">
    <li>
        <i class="fa fa-home fa-fw"></i>
        <?= $this->html->link('Home', '/');?>
    </li>
    <li>
        <?= $this->html->link('radium', '/radium');?>
    </li>
    <li class="active">
        <?= $this->title('Export'); ?>
    </li>
</ol>

<div class="header">
    <div class="col-md-12">
        <h3 class="header-title"><?= $this->title(); ?></h3>
        <!-- <p class="header-info">See a list of all <?= $this->scaffold->plural ?></p> -->
    </div>
</div>

<div class="main-content">
	<?= $this->_render('element', 'radium/form.export'); ?>
</div>

