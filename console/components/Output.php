<?php

namespace Sea\Console\Components;

class Output
{
	
	private static $colors = array(
		'white' 	=> 37,
		'green' 	=> 32,
		'red'		=> 31,
		'blue'		=> 34,
		'cyan'		=> 36,
		'yellow'	=> 33
	);
	
	private static $actions = array(
		'success'	=> 'green',
		'created'	=> 'green',
		'written'	=> 'cyan',
		'skipped'	=> 'blue',
		'failure'	=> 'red',
		'removed'	=> 'red',
		'exception' => 'red',
		'error'		=> 'red',
		'working'	=> 'yellow',
		'invalid'	=> 'yellow',
		'missing'	=> 'yellow'
	);
	
	private $options;
	
	public function __construct(Options $options)
	{
		$this->options = $options;
	}
	
	public function text()
	{
		//if(Command::$options['q'])
		//	return false;

		$outputs = func_get_args();

		foreach($outputs as $output)
			echo $output ."\n";
	}
	
	public function __call($action, $arguments)
	{
		if($this->options->is('q', 'quiet'))
			return false;
		
		foreach($arguments as $argument)
		{
			echo '    ';
			echo "\033[1;". self::$colors[self::$actions[$action]] . "m";
			echo str_pad($action, 12) ."\033[0m $argument\n";
		}
	}
	
}
