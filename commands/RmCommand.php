<?php

# RmCommand class
class RmCommand extends Command
{
	
	protected $syntax = array(
		'controller'	=>	'<controller>',
		'views'			=>	'<controller> [views]'
	);
	
	protected $cmd_options = array(
		'global'		=>	array('quiet' => 'q')
	);
	
	protected $pregs = array(
		'global'	=>	'/^([a-z]+)$/'
	);
	
	public function controller($controller)
	{	
		$name = ucwords($controller) .'Controller';
		
		File::remove('app/controllers/'.$name.'.php');
	}
	
	public function views($controller, $views)
	{
		if(empty($views))
			return Dir::remove('app/views/'.$controller, true);
		
		foreach($views as $view)
		{
			$this->view($controller, $view);
		}
	}
	
	private function view($controller, $view)
	{
		File::remove('app/views/'.$controller.'/'.$view.'.html.php', true);
	}
}

?>