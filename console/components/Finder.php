<?php

namespace Console\Components;

class Finder
{
	protected $resources = array(
		'command_class' => 'Console\\Commands\\%sCommand'
	);
	
	public function __construct()
	{

	}

	public function find($resource, $value)
	{
		if(! isset($this->resources[$resource]))
			throw new \RuntimeException("Resource $resource not defined. Check your ". get_class() ." class.");

		return sprintf($this->resources[$resource], $value);
	}

}