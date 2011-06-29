<?php

# Command class
class Command {
	
	public static $options = array();
	protected $syntax;
	protected $cmd_options = false;
	protected $pregs = false;
	protected $cmd_outside;
	
	public function __construct()
	{
		
	}
	
	public function init($arguments)
	{
		// If working dir is not a Sea project
		if(! is_file(DIR_WORKING . '.sea_project'))
		{
			ExceptionUnless(isset($this->cmd_outside), 'Command not available outside Sea project directory.');
			
			// Set subcommand
			$subcmd = $this->cmd_outside;
		}

		else // If working dir is a Sea project
			// Get subcommand from arguments
			$subcmd = array_shift($arguments);
		
		// Show help if the action method doesn't exist
		if(empty($subcmd) or ! method_exists($this, $subcmd))
			return $this->help();
		
		// Reflection to check type of method
		$Reflection = new ReflectionMethod($this, $subcmd);

		// Show help if the method isn't public
		if(! $Reflection->isPublic())
			return $this->help();
		
		// Get command options
		Command::$options = $this->getOptionsFor($subcmd, $arguments);
		
		// Get command arguments
		$args = $this->getArgsFor($subcmd, $arguments);
		
		// Get number of required parameters
		$num_params	= $Reflection->getNumberOfRequiredParameters();
		
		// Get number of args
		$num_args	= count($args);
		
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
	
	private function getOptionsFor($subcmd, Array &$arguments)
	{
		if(! $this->cmd_options)
			return array();
		
		// Merge arrays of options
		$available = array_merge((array)$this->cmd_options['global'], (array)$this->cmd_options[$subcmd]);
		
		// If no command options
		if(empty($available))
			return array();
		
		// Init option array
		$options = array();
		
		// Look for options from the end of arguments
		for($current = count($arguments) - 1; $arguments[$current][0] == '-'; $current --)
		{
			// Find options like: -v, --version or --path=PATH
			if(preg_match('/^(-([a-z])|--([a-z]+))(=(.*))?$/', $arguments[$current], $matches))
			{
				// Unset option form &arguments
				unset($arguments[$current]);
				
				// If matches[3] is set it means the option is like --option
				if(isset($matches[3]))
				{
					// If option is set in available options
					if(isset($available[$matches[3]]))
						// Key is the value of the available option (like -o)
						$key = $available[$matches[3]];
					
					// If option is not an available option
					else
						continue;
				}
				
				// If matches[3] is not set, it means the option is like -o
				else
				{
					// If the option is in the avaliable options
					if(in_array($matches[2], $available))
						// Key is option name
						$key = $matches[2];
					
					// If the option is not in the available options
					else
						continue;
				}
				
				// If matches[4] is set, it means the option is like -o=VALUE or --option=VALUE
				if(isset($matches[4]))
					// Set option value
					$options[$key] = $matches[5];
				
				// If matches[4] is not set, it means the option is normal
				else
					// Set option to true (active)
					$options[$key] = true;
			}
		}
		
		// Return options array
		return $options;
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
			// If is not an array (GLOBAL expression)
			if(! is_array($pregs))
			{
				// Define anonymous function to check
				$preg_check =
					// An argument as a parameter and use $pregs (GLOBAL expression) and $part by reference
					function($arg) use($pregs)
					{
						// Return if matches
							return (bool)preg_match($pregs, $arg);
					};
			}
			
			// If is an array (SPECIFIC expressions)
			else
			{
				// Define anonymous function to check
				$preg_check =
					// An argument as parameter and use $pregs (array of SPECIFIC expressions) and $part by reference
					function($arg) use($pregs, &$part)
					{
						// Delete '<>' or '[]' of '<PART_NAME>' or '[PART_NAME]'
						$part_name = substr($part, 1, -1);
						
						// If is set an SPECIFIC expression for that PART_NAME and does not match
						if(isset($pregs[$part_name]) && !preg_match($pregs[$part_name], $arg))
							return false;
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
						output('    '. yellow('invalid') .'    '. $argument .' as '. $part);
					
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
	
}

?>