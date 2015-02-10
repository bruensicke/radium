<ol class="breadcrumb">
    <li>
        <i class="fa fa-home fa-fw"></i>
        <?= $this->html->link('Home', '/');?>
    </li>
    <li>
        <?= $this->html->link('radium', '/radium');?>
    </li>
    <li class="active">
        <?= $this->title('Schema'); ?>
    </li>
</ol>

<div class="header">
    <div class="col-md-12">
        <h3 class="header-title"><?= $this->title(); ?></h3>
    </div>
</div>

<div class="main-content">
<?php
    foreach($models as $model) {
        $schema = (is_callable(array($model, 'schema'))) ? $model::schema() : array(); 
        $fields = ($schema) ? $schema->fields() : array();
        ksort($fields);
        echo $this->Handlebars->render('radium/schema', compact('model', 'fields'));
    }
?>
</div>
