<?php

namespace radium\extensions\helper;

class Order extends \lithium\template\helper\Html {

	private static $_conditions = array();

	public static function conditions($conditions){
		self::$_conditions = $conditions;
	}

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
	public static function order($name, $data, $options = array()){
		$order = 'asc';
		$sorts = array('desc' => 'sort-up', 'asc' => 'sort-down');
		$string = ucfirst($name).'<a href="?order=%s:%s%s" class="sort fa fa-%s"></a>';
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
		$conditions = self::enhance();
		$url = sprintf($string, $type, $order, $conditions, $sort);
		echo $url;
	}

	private static function enhance(){
		if(!empty(self::$_conditions)){
			return '&q='.base64_encode(json_encode(self::$_conditions));
		}
		return '';
	}

}
