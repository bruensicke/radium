<?php

namespace radium\extensions\errors;

/**
 * This exception covers the usage of attempting to handle data conversion from or to JSON
 */
class BaseException extends \RuntimeException {

	/**
	 * error code
	 *
	 * @var integer
	 */
	protected $code = 500;

	/**
	 * additional data
	 *
	 * Allows attaching additional data to Exception to be examined in a later step
	 *
	 * @var mixed
	 */
	protected $_data = null;

	/**
	 * allows transport of more information within Exception
	 *
	 * @see radium\extensions\errors\BaseException::getData()
	 * @param mixed $data additional data to be transported
	 * @return mixed $data value of set data
	 */
	public function setData($data) {
		return $this->_data = $data;
	}

	/**
	 * return associated data within this Exception
	 *
	 * @see radium\extensions\errors\BaseException::setData()
	 * @return mixed $data additional data to be transported
	 */
	public function getData() {
		return $this->_data;
	}
}

?>