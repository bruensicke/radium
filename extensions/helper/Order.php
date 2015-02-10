<?php

namespace radium\extensions\helper;

class Order extends \lithium\template\helper\Html {

	/**
	 * order method, to support additional options
	 *
	 * @see lithium\template\helper\Html
	 * @param string $title The name of column,
	 * @param mixed $data Is an array with an ordertype (asc/desc) and a field-value
	 *                    example: 'gender' => 'desc'
	 * @param array $options can contain a field, which will be used for ordering,
	 *                    if name and internal fieldname differs
	 * @return string Returns an `<a />` element.
	 */
	public function order($name, $data, $options = array()){
		$order = 'asc';
		$sorts = array('desc' => 'sort-up', 'asc' => 'sort-down');
		$string = ucfirst($name).'<a href="?order=%s:%s" class="sort fa fa-%s"></a>';
		$sort = 'unsorted';
		$type = $name;
		if(isset($options['field'])){
			$type = $options['field'];
		}
		if(isset($data[$type])){
			$current = $data[$type];
			$sort = $sorts[$data[$type]];
			if($current == 'asc') $order = 'desc';
		}
		echo sprintf($string, $type, $order, $sort);
	}

}
