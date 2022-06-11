<?php declare(strict_types=1);
namespace Dadapas\LogTests;

/**
 * This file is part of the dadapas/log library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use PHPUnit\Framework\TestCase;
use Dadapas\Log\{LogException, Log as Logger};
use Psr\Log\InvalidArgumentException;

final class LogTest extends TestCase
{
	protected $logger;

	/**
	 * @before
	*/
	protected function runFirst()
	{
		$this->logger = new Logger();
		try {
			$this->logger->setPath(dirname(__DIR__, 1) . '/logs');
		} catch (LogException $e) {
			
			$this->assertSame('File is not writable', $e->getMessage());
		}
	}

	public function test_logfile()
	{
		$logger = $this->logger;

		$ret = $logger->error('message has been thrownds', ['balfezge', 'lfezkgjezf']);
		$this->assertSame(null, $ret);
	}

	public function test_using_trycatch()
	{
		$logger = $this->logger;
		try {
			throw new \InvalidArgumentException("not have enough space.");
		} catch (\InvalidArgumentException $e) {

			//var_dump($e->getTrace());die;
			$logger->error($e->getMessage(), $e->getTrace());
			$this->assertInstanceOf(\InvalidArgumentException::class, $e);
		}
	}

	public function test_invalid_level()
	{
		$logger = $this->logger;
		try {
			$logger->log('envalid lavel', 'message for this invalid level' );
		} catch (InvalidArgumentException $e) {

			$logger->error($e->getMessage(), $e->getTrace());
			$this->assertInstanceOf(InvalidArgumentException::class, $e);
		}
	}
}