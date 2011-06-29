<?php

class Output
{
	
	private static $colors = array(
		'white' 	=> "\033[1;37m",
		'green' 	=> "\033[1;32m",
		'red'		=> "\033[1;31m",
		'blue'		=> "\033[1;34m",
		'cyan'		=> "\033[1;36m",
		'yellow'	=> "\033[1;33m"
	);
	
	private static $actions = array(
		'working'	=> 'green',
		'success'	=> 'green',
		'created'	=> 'green',
		'skipped'	=> 'blue',
		'removed'	=> 'red',
		'exception' => 'red',
		'invalid'	=> 'yellow',
		'missing'	=> 'yellow'
	);
	
	public static function text()
	{
		if(Command::$options['q'])
			return false;

		$outputs = func_get_args();

		foreach($outputs as $output)
		{

			echo $output ."\n";
		}
	}
	
	public static function __callStatic($action, $arguments)
	{
		if(Command::$options['q'])
			return false;
		
		foreach($arguments as $argument)
			echo '    '. self::$colors[self::$actions[$action]] . $action ."\033[0m    $argument\n";
	}
	
}

?>