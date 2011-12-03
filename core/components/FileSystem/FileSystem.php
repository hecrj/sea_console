<?php

namespace Core\Components\FileSystem;

class FileSystem
{
	private $dir;
	private $file;
	private $path;
	private $data;
	
	public function __construct(Dir $dir, File $file)
	{
		$this->dir = $dir;
		$this->file = $file;
		$this->data = array();
	}
	
	public function setPath($path)
	{
		if(substr($path, -1) != '/')
			$path .= '/';
		
		$this->path = $path;
		
		return $this;
	}
	
	public function set($key, $data)
	{
		$this->data[$key] = $data;
		
		return $this;
	}
	
	public function generate($template, $filename, $extension = '.php')
	{
		$fullPath = $this->path . $filename . $extension;
		
		if(!file_exists(DIR_WORKING . $this->path))
			$this->dir->create($this->path);
		
		if(! $this->file->create($fullPath))
			return false;
		
		$content = $this->getContentFromTemplate($template);
		
		$this->file->write($content, $fullPath);
	}
	
	private function getContentFromTemplate($template)
	{
		ob_start();
		
		extract($this->data);
		require(DIR . 'templates/'. $template .'.php');
		
		$content = ob_get_contents();
		
		ob_end_clean();
		
		return $content;
	}
	
	public function clean()
	{
		$this->dir->clean($this->path);
	}
	
	public function remove($filename = null, $extension = null)
	{
		if($filename == null)
			$this->dir->remove($this->path);
		else
			$this->file->remove($this->path . $filename . $extension);
	}
}
