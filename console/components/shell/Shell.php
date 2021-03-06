<?php

namespace Console\Components\Shell;

class Shell
{
	private $output;

	public function __construct()
	{

	}

	public function execute($command, $location = null)
	{
		if(! empty($location))
			$command = sprintf('cd %s', $location) .' && '. $command;

		exec($command . ' 2>&1', $data, $error);

		if($error)
			throw new ShellException($command, $data);
	}
}