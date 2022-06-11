<?php
namespace Dadapas\Log;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//use PHPMailer\PHPMailer\SMTP;

class PHPMailerAdapter extends Context implements AdapterInterface
{
	protected $mail;

	protected $template;

	public function __construct(PHPMailer $mailer, $template = "")
	{
		$this->mail = $mailer;
		//$this->template = $template;
		$this->template = dirname(__DIR__)."/templates/email.html";
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
			$str .= "<li>{$trace['file']}({$trace['line']})";
			if (isset($trace['class'])){
				$str .= " {$trace['class']}{$trace['type']}{$trace['function']}";
			} else {
				$str .= " {$trace['function']} args(" . implode(' ', $trace['args']) . ")";
			}
			$str .= "</li>";
			$str .= $sizetrace - 1 == $key ? "" : PHP_EOL;
		}
		return $str;
	}

	/**
	 * Send email to the user who need to get email
	 * 
	 * @param string $level level of log
	 * @param string $message the message to send
	 * @param array $context Context of all application
	 *
	 * @throws \PHPMailer\PHPMailer\Exception
	 * @return null
	*/ 
	public function dispatchLog($level, $message, array $context = array())
	{
		$this->mail->Subject = $message;

		$this->mail->AltBody = "This email is from \"dadapas/log\" ";
		
		$body = file_get_contents($this->template);

		$strTraces = $this->stringifyContext($context);
		
		$format = "Y-m-d H:i:s";

		$changedBody = str_replace('{message}', $message, $body);
		$changedBody = str_replace('{traces}', $strTraces, $changedBody);
		$changedBody = str_replace('{datetime}', date($format), $changedBody);

		echo $changedBody;

		$this->mail->msgHtml($changedBody);
		//$this->mail->send();
	}
}