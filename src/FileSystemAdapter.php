<?php
namespace Dadapas\Log;

use League\Flysystem\Filesystem;

/**
 * This file is part of the dadapas/log library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT 
*/ 
class FileSystemAdapter extends Context implements AdapterInterface
{
	protected $filesystem;

	protected $path;

	public function __construct(Filesystem $filesystem, $path = null)
	{
		$this->filesystem = $filesystem;
	}

	protected function getFile()
	{
		return "dadapas.log";
	}

	public function getFileSystem()
	{
		return $this->filesystem;
	}

	public function dispatchLog($level, $message, array $context = array())
	{
		$date = date('Y-m-d H:i:s');
		$mylevel = strtoupper($level);

		$strcontext = $this->stringifyContext($context);

		$string = "[$date] $mylevel $message " . $strcontext;

		if ( ! $this->filesystem->fileExists($this->getFile()))
			$this->filesystem->write($this->getFile(), "");

		$content = $this->filesystem->read($this->getFile());

		if (empty($content))
			$content = $string;
		else
			$content = $string . PHP_EOL . $content;

		$this->filesystem->write($this->getFile(), $content);
	}
}