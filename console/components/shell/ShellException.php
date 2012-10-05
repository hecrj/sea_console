<?php

namespace Console\Components\Shell;
use Exception;

class ShellException extends Exception
{
	private $data;

	public function __construct($command, $data)
	{
		parent::__construct($command);
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}
}