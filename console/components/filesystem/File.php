<?php

namespace Sea\Console\Components\FileSystem;
use Sea\Console\Components\Output;

class File
{
	private $output;
	
	public function __construct(Output $output)
	{
		$this->output = $output;
	}
	
	public function create($path)
	{
		if(file_exists($path))
		{
			$this->output->skipped($path);
			return false;
		}
		
		if(!@touch($path))
			throw new \RuntimeException('Impossible to create file: '. $path .'. Please, check your directory '.
					'permissions.');
		
		
		$this->output->created($path);
		return true;
	}
	
	public function write($content, $path)
	{
		if(@file_put_contents($path, $content) === FALSE)
			throw new \RuntimeException('Impossible to write contents to file: '. $path .'. Please, check your '.
					'directory permissions.');
		
		$this->output->written($path);
		return true;
	}
	
	public function remove($path)
	{
		if(! is_file($path))
		{
			$this->output->missing($path);
			return false;
		}
		
		if(! @unlink($path))
			throw new \RuntimeException('Impossible to remove file: '. $path .'. Please, check your directory '.
					'permissions.');
		
		$this->output->removed($path);
		return true;
	}
	
}
