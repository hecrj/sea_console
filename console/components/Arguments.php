<?php

namespace Sea\Console\Components;
use Sea\Console\Commands\CommandAbstract;

/**
 * Represents the console arguments.
 *
 * @author Héctor Ramón Jiménez
 */
class Arguments
{
	private $arguments;
	private $types = array(
		'<'		=>	'required',
		'['		=>	'group'
	);
	
	public function __construct(Array $arguments)
	{
		$this->arguments = $arguments;
	}
	
	public function get()
	{
		return $this->arguments;
	}
	
	public function extract($preg)
	{
		
		$arguments = array();
		
		$key = count($this->arguments) - 1;
		
		while($key >= 0 and preg_match($preg, $this->arguments[$key], $match))
		{
			$arguments[] = $match;
			unset($this->arguments[$key]);

			--$key;
		}
		
		return $arguments;
	}
	
	public function shift()
	{
		return array_shift($this->arguments);
	}
	
	public function prepare($syntax)
	{
		if(empty($syntax))
			return false;
		
		$arguments = array();
		$parts = explode(' ', $syntax);
		
		foreach($parts as $part)
		{
			if(! isset($this->types[$part[0]]))
				throw new \RuntimeException('Undefined syntax type for: '. $part .'. Check '. get_class($this));
			
			$argTypeMethod = 'set'. ucfirst($this->types[$part[0]]) .'Arg';
			$arguments[] = $this->$argTypeMethod($part);
		}
		
		$this->arguments = $arguments;
	}
	
	private function setRequiredArg($name)
	{
		$arg = $this->shift();
		
		if(empty($arg))
			throw new \RuntimeException('Missing required argument: '. $name);
		
		return escapeshellcmd($arg);
	}
	
	private function setGroupArg($name)
	{
		$args = array();
		
		foreach($this->arguments as $arg)
			$args[] = escapeshellcmd($arg);
		
		return $args;
	}
}
