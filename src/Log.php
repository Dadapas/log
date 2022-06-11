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

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

class Log extends AbstractLogger
{
	protected $adapter;

	public function __construct(AdapterInterface $loggerAdapter = null)
	{
		$this->adapter = $loggerAdapter;
	}

	protected function alerts()
	{
		return array(
            LogLevel::EMERGENCY => array(LogLevel::EMERGENCY, 'message of level emergency with context: {user}'),
            LogLevel::ALERT => array(LogLevel::ALERT, 'message of level alert with context: {user}'),
            LogLevel::CRITICAL => array(LogLevel::CRITICAL, 'message of level critical with context: {user}'),
            LogLevel::ERROR => array(LogLevel::ERROR, 'message of level error with context: {user}'),
            LogLevel::WARNING => array(LogLevel::WARNING, 'message of level warning with context: {user}'),
            LogLevel::NOTICE => array(LogLevel::NOTICE, 'message of level notice with context: {user}'),
            LogLevel::INFO => array(LogLevel::INFO, 'message of level info with context: {user}'),
            LogLevel::DEBUG => array(LogLevel::DEBUG, 'message of level debug with context: {user}'),
        );
	}

	protected function isValidLevel($level)
	{
		$levels = $this->alerts();
		if ( ! array_key_exists($level, $levels) )
			throw new InvalidArgumentException("Invalid level ". $level);
	}

	/**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     * @throws LogException
     */
	public function log($level, $message, array $context = array())
	{
		if (is_null($this->adapter))
			throw new LogException("No adapter has been found.");

		$this->isValidLevel($level);
		
		$this->adapter->dispatchLog($level, $message, $context);
	}
	/**
	 * Change the current adapter
	 * 
	 * @param AdapterInterface $adapter the adapter interface
	 * @return void
	*/ 
	public function setAdapter(AdapterInterface $adapter)
	{
		$this->adapter = $adapter;
	}
}