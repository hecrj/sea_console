<?php

namespace Core\Components\FileSystem;
use Core\Components\Output;

class File
{
	private $output;
	
	public function __construct(Output $output)
	{
		$this->output = $output;
	}
	
	public function create($path)
	{
		if(file_exists(DIR_WORKING . $path))
		{
			$this->output->skipped($path);
			return false;
		}
		
		if(!@touch(DIR_WORKING . $path))
			throw new \RuntimeException('Impossible to create file: '. $path .'. Please, check your directory '.
					'permissions.');
		
		
		$this->output->created($path);
		return true;
	}
	
	public function write($content, $path)
	{
		if(@file_put_contents(DIR_WORKING . $path, $content) === FALSE)
			throw new \RuntimeException('Impossible to write contents to file: '. $path .'. Please, check your '.
					'directory permissions.');
		
		$this->output->written($path);
		return true;
	}
	
	public function remove($path)
	{
		if(! is_file(DIR_WORKING . $path))
		{
			$this->output->missing($path);
			return false;
		}
		
		if(! @unlink(DIR_WORKING . $path))
			throw new \RuntimeException('Impossible to remove file: '. $path .'. Please, check your directory '.
					'permissions.');
		
		$this->output->removed($path);
		return true;
	}
	
}
