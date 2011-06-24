<?php

class Handler {
	
	public static $options;
	private static $console_path;
	private static $controller;
	private static $action;
	private static $arguments;
	
	public static function getControllerFor($arguments, $num)
	{
		// Console path
		self::$console_path = array_shift($arguments);
		
		// Option search
		$options = array();
		
		// Last key to look for options
		$current = count($arguments) - 1;
		
		// While last argument starts with '-'
		while($arguments[$current][0] == '-')
		{	
			if(preg_match('/^--?([a-z]+)(=(.*))?$/', $arguments[$current], $matches))
			{
				if(isset($matches[3]))
					$options[$matches[1]] = $matches[3];
				else
					$options[$matches[1]] = true;
			}
			
			unset($arguments[$current]);
			$current --;
		}
		
		// Options
		self::$options = $options;
		
		// Action
		self::$action = (array_shift($arguments)) ? : 'help';
		
		// Controller
		if(! is_file(WDIR . '.sea_project'))
			self::$controller = 'project';
			
		else
			self::$controller = (array_shift($arguments)) ? : 'main';
		
		// Arguments
		self::$arguments = $arguments;
		
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