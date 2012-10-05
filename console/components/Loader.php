<?php

namespace Console\Components;

# Autoloader class
class Loader
{

	public function __construct()
	{
		
	}
	
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'), true, false);
	}	
	
	public function loadClass($name)
	{
		$namespaces = explode('\\', $name);
		$path = array_pop($namespaces) .'.php';

		if(!empty($namespaces))
		{
			if($namespaces[0] == 'Sea') array_shift($namespaces);

			$path = strtolower(implode(DIRECTORY_SEPARATOR, $namespaces)) . DIRECTORY_SEPARATOR . $path;
		}
		
		if(! is_file(\Console\DIR . $path))
			throw new \Exception("Unable to load class: $name");
		
		require(\Console\DIR . $path);
		
		return true;
	}

	public function loadFile($rel_path, Array $data = null)
	{
		extract((array) $data);

		$abs_path = \Console\DIR . $rel_path;

		if(!is_file($abs_path))
			throw new \RuntimeException("Unable to load file: $abs_path");

		require($abs_path);
	}
	
}
