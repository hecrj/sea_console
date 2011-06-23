<?php

class Controller {
	
	private $action;
	
	public function __construct()
	{
		
	}
	
	public function init($action, $arguments)
	{
		$caction = ($action == 'new') ? '_new' : $action;
		
		// Exit if the action method doesn't exist
		if(! method_exists($this, $caction))
			exit('Invalid command: '. $action ."\n");

		// Reflection to check type of method
		$Reflection = new ReflectionMethod($this, $caction);

		// Exit if the method isn't public
		if(! $Reflection->isPublic())
			exit('Invalid command: '. $action ."\n");

		call_user_func_array(array($this, $caction), $arguments);
	}
	
}

?>