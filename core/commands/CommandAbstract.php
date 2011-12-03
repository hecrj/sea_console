<?php

namespace Core\Commands;
use Core\Components\DynamicInjector;
use Core\Components\Arguments;

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
		if(! is_file(DIR_WORKING . '.sea_project'))
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
	
	private function getArgsFor($subcmd, Array $arguments)
	{
		// Throw exception if subcommand syntax does not exist
		if(!isset($this->syntax[$subcmd]))
			throw new Exception('Unspecified syntax for command: '. Console::getCommand() .' '. $subcmd);
			
		// If syntax is empty, return empty array of arguments
		if(empty($this->syntax[$subcmd]))
			return array();
		
		// Init args array
		$args = array();
		
		// Explode syntax
		$parts = explode(' ', $this->syntax[$subcmd]);
		
		// If regular expressions are defined (FALSE by default)
		if($pregs = $this->pregs)
		{
			// If global is set (GLOBAL expression)
			if(isset($pregs['global']))
			{
				// Define anonymous function to check
				$preg_check =
					// An argument as a parameter and use $pregs (GLOBAL expression) and $part by reference
					function($arg) use($pregs, &$part)
					{
						// Delete '<>' or '[]' of '<PART_NAME>' or '[PART_NAME]'
						$part_name = substr($part, 1, -1);
						
						// If an specific expression exists
						if(isset($pregs[$part_name]))
						{
							// And evaluates as true
							if($pregs[$part_name])
								// Return if matches
								return (bool)preg_match($pregs[$part_name], $arg);
								
							// If evaluates as false
							else
								// Don't check and return true
								return true;
						}
						
						// If an specific expression does not exist
						else
							// Return if global matches
							return (bool)preg_match($pregs['global'], $arg);
					};
			}
			
			// If global is not set (SPECIFIC expressions only)
			else
			{
				// Define anonymous function to check
				$preg_check =
					// An argument as parameter and use $pregs (array of SPECIFIC expressions) and $part by reference
					function($arg) use($pregs, &$part)
					{
						// Delete '<>' or '[]' of '<PART_NAME>' or '[PART_NAME]'
						$part_name = substr($part, 1, -1);
						
						// If is set an SPECIFIC expression for that PART_NAME, evaluates to true and does not match
						if(isset($pregs[$part_name]) and $pregs[$part_name] and !preg_match($pregs[$part_name], $arg))
							return false;
							
						// In any other case, return true
						else
							return true;
					};
			}
		}
		
		// If regular expressions are not defined
		else
			// Set an always return true anonymous function to check nothing
			$preg_check = function(){ return true; };
		
		// Iterate over syntax parts
		foreach($parts as $part)
		{
			// If part starts with an < char, it means that is a required argument
			if($part[0] == '<')
			{
				// Get required argument
				$required = array_shift($arguments);
				
				// If the argument is empty
				if(empty($required))
					// Throw an exception
					throw new Exception('Missing argument: '. $part);
				
				// Check regular expression
				if(! $preg_check($required))
					throw new Exception('Invalid argument value: '. $required .' as '. $part);
				
				// Add argument to args
				$args[] = $required;
			}
			
			// If part does not start with an < char, it means that is a group argument
			else
			{
				// Init group array
				$group = array();
				
				// Collect all arguments left...
				foreach($arguments as $argument)
				{
					// Check regular expression
					if(! $preg_check($argument))
					{
						Output::invalid($argument .' as '. $part);
						continue;
					}
					
					// Set argument into group
					$group[] = $argument;
				}
				
				// Add group to args
				$args[] = $group;
				
				// End foreach
				break;
			}
		} 
		
		// Return args
		return $args;
	}
	
	public static function getCommandClass($commandName)
	{
		return __NAMESPACE__ .'\\'. ucfirst($commandName) .'Command';
	}
	
}

?>