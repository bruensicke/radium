<ol class="breadcrumb">
    <li>
        <i class="fa fa-home fa-fw"></i>
        <?= $this->html->link('Home', '/');?>
    </li>
    <li>
        <?= $this->html->link('radium', '/radium');?>
    </li>
    <li class="active">
        <?= $this->title('Settings'); ?>
    </li>
</ol>

<div class="header">
    <div class="col-md-12">
        <h3 class="header-title"><?= $this->title(); ?></h3>
    </div>
</div>

<div class="main-content">

<table class="table table-striped table-condensed">
    <colgroup>
        <col width="170" />
        <col width="*" />
    </colgroup>
    <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
    <?php if(empty($settings)): ?>
        <tr>
            <td colspan="2"><h5>No settings found...</h5></td>
        </tr>
    <?php endif; ?>
    <?php foreach($settings as $setting): ?>
        <tr data-id="<?= $setting->id() ?>" data-value="">
            <td class="key"><?= $setting->name ?></td>
            <td class="value">
<?php 
switch($setting->type) {
    case 'navigation':
        echo $this->Navigation->render($setting->val());
        break;
    case 'ini':
    case 'json':
    case 'neon':
    case 'array':
        echo $this->scaffold->render('data', array('data' => $setting->val(null, array('flat' => true))));
        break;
    case 'list':
        echo '<div class="well pre-scrollable">';
        echo $this->scaffold->render('list', array('data' => $setting->val(null, array('flat' => true))));
        echo '</div>';
        break;
    case 'string':
        echo '<p class="well">'.$setting->value.'</p>';
        break;
    case 'boolean':
        $val = $setting->val();
        $label = ($val) ? 'true' : 'false';
        echo '<span class="label label_'.$label.'">'.$label.'</span>';
        break;
}
 ?>


            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</div>
