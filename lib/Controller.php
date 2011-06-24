<?php

class Controller {
	
	private $action;
	
	public function __construct()
	{
		
	}
	
	public function init($command, $args)
	{
		$action = ($command == 'new') ? '_new' : $command;
		
		// Exit if the action method doesn't exist
		if(! method_exists($this, $action))
			exit('Invalid command: '. $command ."\n");

		// Reflection to check type of method
		$Reflection = new ReflectionMethod($this, $action);

		// Exit if the method isn't public
		if(! $Reflection->isPublic())
			exit('Invalid command: '. $command ."\n");
		
		$num_params	= $Reflection->getNumberOfParameters();
		$num_args	= count($args);
		
		if($num_params > $num_args)
			$num_args = $num_params;
		
		switch ($num_args)
		{
		    case 0: return $this->$action(); break;
		    case 1: return $this->$action($args[0]); break;
		    case 2: return $this->$action($args[0], $args[1]); break;
		    case 3: return $this->$action($args[0], $args[1], $args[2]); break;
		    case 4: return $this->$action($args[0], $args[1], $args[2], $args[3]); break;
		    case 5: return $this->$action($args[0], $args[1], $args[2], $args[3], $args[4]); break;
		    case 6: return $this->$action($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]); break;
		    case 7: return $this->$action($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]); break;
		    case 8: return $this->$action($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]); break;
		    case 9: return $this->$action($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]); break;
		    default: return call_user_func_array(array($this, $action), $args);
		}
	}
	
}

?>