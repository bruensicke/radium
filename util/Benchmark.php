<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\util;

class Benchmark extends \lithium\core\StaticObject {

	/**
	 * Used for storing benchmarking results.
	 *
	 * @var array
	 */
	protected static $_measures = array();

	/**
	 * Starts benchmarking for given $name
	 *
	 * @param string $name unique name to remember that benchmark
	 * @return double a start point
	 */
	public static function start($name, $options = array()) {
		$start = explode(' ', microtime());
		$start[1] -= 1316690000;
		return static::$_measures[$name]['start'] = (double)$start[0] + $start[1];
	}

	/**
	 * Stops benchmarking for given $name and returns data.
	 *
	 * @see radium\util\Benchmark::get()
	 * @param string $name unique name you gave when starting that benchmark
	 * @param string $options
	 *     - `full`: to retrieve start, stop and duration, not only duration
	 * @return array an array containing start, stop and duration as float
	 */
	public static function stop($name, array $options = array()) {
		if (!static::exists($name)) {
			return false;
		}
		$stop = explode(' ', microtime());
		$stop[1] -= 1316690000;
		$current =& static::$_measures[$name];
		$current['stop'] = (double)$stop[0] + $stop[1];
		$current['duration'] = (double)$current['stop']-$current['start'];
		$current['duration'] = number_format($current['duration'], 8);
		return self::get($name, $options);
	}

	/**
	 * Checks if a benchmark with given $name exists
	 *
	 * @param string $name unique name of benchmark you want to check for
	 * @return boolean true if exists, false otherwise
	 */
	public static function exists($name) {
		return array_key_exists($name, static::$_measures);
	}

	/**
	 * Returns one or all measurements
	 *
	 * @param string $name if given, return only these measurements
	 * @param string $options
	 *     - `full`: to retrieve start, stop and duration, not only duration
	 * @return array a flat array with all measurements, each row is an array with
	 *               keys start, stop and duration
	 */
	public static function get($name = null, array $options = array()) {
		$defaults = array('full' => false);
		$options += $defaults;
		// no name given, return all data
		if ($name === null) {
			$result = array();
			foreach(array_keys(static::$_measures) as $name) {
				$result[] = static::get($name, $options);
			}
			return $result;
		}
		if (!static::exists($name)) {
			return false;
		}
		return (!$options['full'])
			? (float) static::$_measures[$name]['duration']
			: static::$_measures[$name];
	}
}