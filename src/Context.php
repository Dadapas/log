<?php
namespace Dadapas\Log;

/**
 * Context class
 * 
 * This file is part of the dadapas/log library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT 
*/ 
class Context
{
	/**
	 * Stringify array traces
	 * 
	 * @return string 
	*/ 
	protected function stringifyContext(array $traces)
	{
		if (empty($traces)) return "";
		
		if ( ! isset($traces[0]['function']) )
			return implode(PHP_EOL, array_values($traces));

		$str = "";
		$sizetrace = count($traces);
		foreach($traces as $key => $trace)
		{
			$str .= "#$key {$trace['file']}({$trace['line']})";
			if (isset($trace['class'])){
				$str .= " {$trace['class']}{$trace['type']}{$trace['function']}";
			} else {
				$str .= " {$trace['function']} args(" . implode(' ', $trace['args']) . ")";
			}
			$str .= $sizetrace - 1 == $key ? "" : PHP_EOL;
		}
		return $str;
	}
}