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
use Dadapas\Log\FileSystemAdapter;
use Dadapas\Log\PHPMailerAdapter;
use Psr\Log\InvalidArgumentException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;
use Exception;

final class LogTest extends TestCase
{
	protected $loggerFile;

	protected $loggerEmail;

	/**
	 * @before
	*/
	protected function runFirst()
	{
		$this->loggerFile = new Logger();
		$this->loggerEmail = new Logger();

		$adapter = new \League\Flysystem\Local\LocalFilesystemAdapter(
		    dirname(__DIR__).'/logs'
		);
		$filesystem = new \League\Flysystem\Filesystem($adapter);

		$filesystemAdapter = new FileSystemAdapter($filesystem);

		// Local file adapter
		$this->loggerFile->setAdapter($filesystemAdapter);

		// Set up a php mailer logger
		//
		$mail = new PHPMailer(true);

		//Server settings
	    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	    $mail->isSMTP();                                            //Send using SMTP
	    $mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = 'emai@gmail.com';

		//Password to use for SMTP authentication
		$mail->Password = 'password';

		//Set who the message is to be sent from
		//Note that with gmail you can only use your account address (same as `Username`)
		//or predefined aliases that you have configured within your account.
		//Do not use user-submitted addresses in here
		$mail->setFrom('fromemail@gmail.com', 'First Last');

		$mail->addAddress('toemaiil@email.com', 'Sencond last');

	    $emailAdapter = new PHPMailerAdapter($mail);

		// Email adapter
		$this->loggerEmail->setAdapter($emailAdapter);
	}

	public function test_logfile()
	{
		$logger = $this->loggerFile;

		try {
		    throw new Exception("An exception has been thrown.");
		} catch (Exception $e) {

			$ret = $logger->error($e->getMessage(), $e->getTrace());
			$this->assertSame(null, $ret);
		}
	}

	/*public function test_logemail()
	{
		$logger = $this->loggerEmail;

		try {
		    throw new Exception("An exception has been thrown.");
		} catch (Exception $e) {

			$ret = $logger->error($e->getMessage(), $e->getTrace());
			$this->assertSame(null, $ret);
		} catch (MailerException $e) {

		    $this->assertSame("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", $e->getMessage());
		}
	}*/

}
