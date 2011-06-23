<?php

class Handler {
	
	private static $console_path;
	private static $controller;
	private static $action;
	private static $arguments;
	
	public static function getControllerFor($arguments, $num)
	{
		// Console path
		self::$console_path = array_shift($arguments);
		
		// Action
		self::$action = (array_shift($arguments)) ? : 'help';
			
		if(! is_file(WDIR . '.sea_project'))
			self::$controller = 'project';
			
		else
			self::$controller = (array_shift($arguments)) ? : 'main';

		// Pop arguments
		self::$arguments = array_pad((array)$arguments, 5, 0);
		
		// Controller name
		$controller_name = ucwords(self::$controller) . 'Controller';
		
		// Controller path
		$controller_path = DIR . '/controllers/' . $controller_name . '.php';
		
		if(! is_file($controller_path))
			exit('Invalid object: '. self::$controller ."\n");
			
		require($controller_path);
		
		return new $controller_name();
	}
	
	public static function getConsolePath()
	{
		return self::$console_path;
	}
	
	public static function getController()
	{
		return self::$controller;
	}
	
	public static function getAction()
	{
		return self::$action;
	}
	
	public static function getArguments()
	{
		return self::$arguments;
	}
	
}

?>