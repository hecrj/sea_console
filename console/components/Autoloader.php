<?php

namespace Sea\Console\Components;

# Autoloader class
class Autoloader
{	
	public function __construct()
	{}
	
	public function register()
	{
		spl_autoload_register(array($this, 'load'), true, false);
	}	
	
	public function load($name)
	{
		$namespaces = explode('\\', $name);
		$class_name = array_pop($namespaces);

		if($namespaces[0] == 'Sea')
			array_shift($namespaces);

		$path = strtolower(implode(DIRECTORY_SEPARATOR, $namespaces)) . DIRECTORY_SEPARATOR . $class_name. '.php';
		
		if(! is_file(DIR . $path))
			throw new \Exception('Unable to load class: '. $name);
		
		require(DIR . $path);
		
		return true;
	}
	
}
