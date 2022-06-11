<?php
namespace Dadapas\Log;

/**
 * This file is part of the dadapas/log library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT 
*/ 
interface AdapterInterface
{
	/**
	 * Action that will be triggers
	 * when log has been made
	 * 
	 * @param string $level This could be error, alert, emergency, ...
	 * @param string $message The message log
	 * @param array $context = []
	 * 
	 * @throws LogException 
	*/ 
	public function dispatchLog($level, $message, array $context = array());

}