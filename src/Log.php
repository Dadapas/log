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
	protected $path;

	public function __construct(string $path = "")
	{
		$this->path = $path;
	}

	protected function isPathWritable()
	{
		$file = $this->getFile();
		if ( empty($this->path) || is_null($this->path) )
			throw new InvalidArgumentException("Path is not a valid path.");

		if ( ! file_exists($file) )
			file_put_contents($file, '');


		if ( ! is_writable($file) )
			throw new LogException('File is not writable');
	}

	public function alerts()
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

	protected function getFile()
	{
		return "{$this->path}/dadapas.log";
	}

	protected function write(string $data)
	{
		$this->isPathWritable();
		$file = $this->getFile();

		file_put_contents($file, $data);
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
     */
	public function log($level, $message, array $context = array())
	{
		$this->isValidLevel($level);
		$date = date('Y-m-d H:i:s');
		$mylevel = strtoupper($level);

		$strcontext = $this->stringifyContext($context);

		$string = "[$date] $mylevel $message " . $strcontext;
		$content = file_get_contents($this->getFile());

		if (empty($content))
			$content = $string;
		else
			$content = $string . PHP_EOL . $content;

		$this->write($content);
	}

	public function setPath(string $path)
	{
		$this->path = $path;
		$this->isPathWritable();
	}

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