<?php

namespace Console;

use Console\Components\Shell\ShellException;
use Console\Components\DynamicInjector;
use Exception;

class Console {
	
	private $args;
	
	public function __construct(Array $args)
	{
		array_shift($args); // Throw path argument
		$this->args = $args;
	}
	
	public function init(DynamicInjector $injector)
	{
		require(__DIR__ . '/config/console.php');
		define("NAME_PREG", '/^([a-z]+)$/');
		
		try
		{
			$arguments = $injector->get('arguments', array($this->args));
			$options = $injector->get('options');

			$opt = $arguments->extract($options->getFormat());
			$options->setOptions($opt);
			
			$command_name = $arguments->shift() ?: 'help';

			$finder = $injector->get('finder');
			$command_class = $finder->find('command_class', $command_name);
			
			$command = new $command_class($injector);
			$command->init($arguments);
		}

		catch(Exception $e)
		{
			$output = $injector->get('output');
			$output->exception($e->getMessage());

			if($e instanceof ShellException)
				foreach($e->getData() as $error)
					$output->error($error);
			
			exit;
		}
	
	}
	
}
