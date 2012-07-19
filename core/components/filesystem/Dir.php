<?php

namespace Core\Components\FileSystem;
use Core\Components\Output;

class Dir
{
	private $output;
	private $file;
	
	public function __construct(Output $output, File $file)
	{
		$this->output = $output;
		$this->file = $file;
	}
	
	public function create($path)
	{
		if(!@mkdir($path, 0755, true))
			throw new \RuntimeException('Impossible to create directory: '. $path);
		
		$this->output->created($path);
		return true;
	}
	
	public function clean($path)
	{
		if(! file_exists($path))
		{
			$this->output->missing($path);
			return false;
		}
		
		if(! is_dir($path))
			throw new \RuntimeException('Impossible to clean the path: '. $path .' because is not a directory.');
		
		$this->removeFiles($path);
		return true;
	}
	
	private function removeFiles($path)
	{
		$objects = scandir($path); 
			
		foreach($objects as $object)
		{
			if($object == '.' or $object == '..')
				continue;
			
			if(is_dir($path . $object))
				$this->remove($path . $object . '/');

			else
				$this->file->remove($path . $object);
		}
	}
	
	public function remove($path)
	{
		if(! $this->clean($path))
			return false;
		
		if(!@rmdir($path))
			throw new \RuntimeException('Impossible to remove directory: '. $path .'. Check the directory permissions.');
		
		$this->output->removed($path);
		return true;
	}
	
}
