<?php
use lithium\util\String;

$url = $this->url();
$content = array();
$select = <<<html
	<a href="</a>
html;
$link = <<<html
	<a href="{:url}/{:field}:{:key}" class="btn {:active}" data-field="{:field}" data-value="{:value}" data-key="{:key}">{:value}</a>
html;

$template = (count($filters) > 8)
	? 'select'
	: 'link';

if (empty($filter) && !empty($types)) {
	$filter = $types;
}
foreach($filters as $field => $values) {
	$filter = array_map(function($key, $value) use ($field) {
			return compact('field', 'key', 'value');
		}, array_keys($values), $values);
	$content[] = '		<div class="btn-group" data-toggle="buttons-radio">';
	foreach($filter as $data) {
		$data['url'] = $url;
		$content[] = String::insert($$template, $data, array('clean' => true));
	}
	$content[] = '</div>';
}
debug($options);
debug($content);
?>
<div class="tabbable">
	<div class="breadcrumb">
		<?php #echo $this->_render('element', 'search'); ?>
		<div id="tab" class="btn-group" data-toggle="buttons-radio">
			<?php echo implode("\n", $content); ?>
		</div>
	</div>
	<div id="result"></div>
</div>
