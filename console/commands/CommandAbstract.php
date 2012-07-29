<?php

namespace Sea\Console\Commands;
use Sea\Console\Components\DynamicInjector;
use Sea\Console\Components\Arguments;

abstract class CommandAbstract {
	
	protected $options = array();
	protected $syntax;
	protected $cmd_options = false;
	protected $pregs = false;
	protected $cmd_outside;
	private $injector;
	
	
	public function __construct(DynamicInjector $injector)
	{
		$this->injector = $injector;
	}
	
	protected function get($name)
	{
		return $this->injector->get($name);
	}
	
	public function init(Arguments $arguments)
	{
		// If working dir is not a Sea project
		if(! is_file('.sea_project'))
		{
			if(! isset($this->cmd_outside))
					throw new \RuntimeException('This command is not available outside Sea project directory.');
			
			// Set subcommand
			$subcmd = $this->cmd_outside;
		}

		else
			$subcmd = $arguments->shift();
		
		// Show help if the action method doesn't exist
		if(empty($subcmd) or ! method_exists($this, $subcmd))
			return $this->help();
		
		// Reflection to check type of method
		$Reflection = new \ReflectionMethod($this, $subcmd);

		// Show help if the method isn't public
		if(! $Reflection->isPublic())
			return $this->help();
		
		// Get command arguments
		$arguments->prepare($this->syntax[$subcmd]);
		
		// Get number of required parameters
		$num_params	= $Reflection->getNumberOfRequiredParameters();
		
		$args = $arguments->get();
		
		// Get number of args
		$num_args = count($args);
		
		// If there are more required parameters than args
		if($num_params > $num_args)
			$num_args = $num_params;
		
		// Switch to avoid call_user_func_array depending the number of args
		switch ($num_args)
		{
		    case 0: return $this->$subcmd(); break;
		    case 1: return $this->$subcmd($args[0]); break;
		    case 2: return $this->$subcmd($args[0], $args[1]); break;
		    case 3: return $this->$subcmd($args[0], $args[1], $args[2]); break;
		    case 4: return $this->$subcmd($args[0], $args[1], $args[2], $args[3]); break;
		    case 5: return $this->$subcmd($args[0], $args[1], $args[2], $args[3], $args[4]); break;
		    case 6: return $this->$subcmd($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]); break;
		    case 7: return $this->$subcmd($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]); break;
		    case 8: return $this->$subcmd($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]); break;
		    case 9: return $this->$subcmd($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]); break;
		    default: return call_user_func_array(array($this, $subcmd), $args);
		}
	}
	
	public function help()
	{
		// Default subcommand help
	}
	
}
