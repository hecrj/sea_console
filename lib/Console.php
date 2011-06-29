<?php

class Console {
	
	private static $path;
	private static $command;
	private static $object;
	private static $arguments;
	
	public static function init($arguments, $num)
	{
		// Define Sea project directory
		define(DIR_PROJECT, DIR . 'project/');
		
		// Define Sea core directory
		define(DIR_CORE, DIR_PROJECT . 'core/');
		
		// Define working directory
		define(DIR_WORKING, getcwd().'/');
		
		// File names and directory names regular expression
		define(NAME_PREG, '/^([a-z]+)$/');

		// Load functions
		require(DIR . 'lib/Functions.php');
		
		// Load command base class
		require(DIR . 'lib/Command.php');
		
		// Load file class
		require(DIR . 'lib/File.php');
		
		// Load dir class
		require(DIR . 'lib/Dir.php');
		
		// Console path
		self::$path = array_shift($arguments);
		
		// Command
		self::$command = (array_shift($arguments)) ? : 'help';
		
		// Controller name
		$command_name = ucwords(self::$command) . 'Command';
		
		// Controller path
		$command_path = DIR . '/commands/' . $command_name . '.php';

		try
		{
			// Exception if controller file does not exist
			ExceptionUnless(is_file($command_path), 'Invalid command: '. self::$command);
			
			// Load controller file
			require($command_path);
			
			// Instantiate controller
			$Command = new $command_name;
			
			// Initialize controller
			$Command->init($arguments);
		}

		catch(Exception $e)
		{
			// Output exception info and exit
			exit('    '. red('exception') .'    '. $e->getMessage() ."\n");
		}
	
	}
	
	public static function getPath()
	{
		return self::$path;
	}
	
	public static function getCommand()
	{
		return self::$command;
	}
	
	public static function getObject()
	{
		return self::$object;
	}
	
	public static function getArguments()
	{
		return self::$arguments;
	}
	
}

?>