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
    </div>
</div>

<div class="main-content">
    <h4>Content</h4>
    <ul class="nav nav-pills">
        <li><?= $this->html->link('Assets', array('library' => 'radium', 'controller' => 'assets', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Configurations', array('library' => 'radium', 'controller' => 'configurations', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Contents', array('library' => 'radium', 'controller' => 'contents', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Pages', array('library' => 'radium', 'controller' => 'pages', 'action' => 'index')); ?></li>
        <li><?= $this->html->link('Versions', array('library' => 'radium', 'controller' => 'versions', 'action' => 'index')); ?></li>
    </ul>
    <hr />
    <h4>Extras</h4>
    <ul class="nav nav-pills">
        <li><?= $this->html->link('Settings', array('library' => 'radium', 'controller' => 'radium', 'action' => 'settings')); ?></li>
        <li><?= $this->html->link('Schema', array('library' => 'radium', 'controller' => 'radium', 'action' => 'schema')); ?></li>
        <li><?= $this->html->link('Request', array('library' => 'radium', 'controller' => 'radium', 'action' => 'request')); ?></li>
        <li><?= $this->html->link('Export', array('library' => 'radium', 'controller' => 'radium', 'action' => 'export')); ?></li>
    </ul>
</div>
