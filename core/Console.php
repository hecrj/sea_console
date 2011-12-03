<?php

namespace Core;

class Console {
	
	private $classes;
	private $path;
	
	public function __construct(Array $classes)
	{
		$this->classes = $classes;
	}
	
	public function init($arguments, $num)
	{	
		define(NAME_PREG, '/^([a-z]+)$/');
		
		$injector = new $this->classes['Injector'];
		
		try
		{	
			$this->path = array_shift($arguments);
			
			$arguments = new $this->classes['Arguments']($arguments);
			$options = $injector->get('options');
			$options->extractOptionsFrom($arguments);
			
			$commandName = $arguments->shift() ?: 'help';
		
			$commandAbstractClass = $this->classes['Command'];
			$commandClass = $commandAbstractClass::getCommandClass($commandName);
			
			$command = new $commandClass($injector);
			$command->init($arguments);
		}

		catch(\Exception $e)
		{
			$output = $injector->get('output');
			$output->exception($e->getMessage());
			
			exit;
		}
	
	}
	
	public function getPath()
	{
		return $this->path;
	}
	
}