<?php

namespace Sea\Console\Components;
use Sea\Console\Commands\CommandAbstract;

/**
 * Represents the command options.
 *
 * @author Héctor Ramón Jiménez
 */
class Options
{
	const OPTION_PREG = '/^(-(?P<simple>[a-z])|--(?P<extended>[a-z-]+))(=(?P<value>.*))?$/';
	private $available = array();
	private $options = array();
	
	public function __construct()
	{}
	
	public function setAvailableOptions(Array $available)
	{
		$this->available = $available;
	}
	
	public function extractOptionsFrom(Arguments $arguments)
	{	
		$options = $arguments->extract(self::OPTION_PREG);
		
		foreach($options as $option)
			$this->setOption($option);
	}
	
	private function setOption($option)
	{
		$name = $option['simple'] ?: $option['extended'];
		
		if(isset($option['value']))
			$this->options[$name] = $option['value'];
		else
			$this->options[$name] = true;
	}
	
	public function get($simple = null, $extended = null)
	{
		if($simple == null)
			return $this->options;
		
		if(isset($this->options[$simple]))
			return $this->options[$simple];
		
		if($extended != null and isset($this->options[$extended]))
			return $this->options[$extended];
		
		return false;
	}
	
	public function is($simple, $extended = null)
	{
		return (bool) $this->get($simple, $extended);
	}
}
