<ol class="breadcrumb">
    <li>
        <i class="fa fa-home fa-fw"></i>
        <?= $this->html->link('Home', '/');?>
    </li>
    <li>
        <?= $this->html->link('radium', '/radium');?>
    </li>
    <li class="active">
        <?= $this->title('Dashboard'); ?>
    </li>
</ol>

<div class="header">
    <div class="col-md-12">
        <h3 class="header-title"><?= $this->title(); ?></h3>
        <!-- <p class="header-info">See a list of all <?= $this->scaffold->plural ?></p> -->
    </div>
</div>

<div class="main-content">
    <ul class="nav nav-pills">
        <li><?= $this->html->link('Assets', array('library' => 'radium', 'controller' => 'assets', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Configurations', array('library' => 'radium', 'controller' => 'configurations', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Contents', array('library' => 'radium', 'controller' => 'contents', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Versions', array('library' => 'radium', 'controller' => 'versions', 'action' => 'index')); ?></li>
    </ul>
    <hr />
    <ul class="nav nav-pills">
        <li><?= $this->html->link('Settings', array('library' => 'radium', 'controller' => 'radium', 'action' => 'settings')); ?></li>
        <li><?= $this->html->link('Export', array('library' => 'radium', 'controller' => 'radium', 'action' => 'export')); ?></li>
    </ul>
</div>
