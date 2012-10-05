<?php

namespace Console\Commands;

class RmCommand extends CommandAbstract
{
	
	protected $syntax = array(
		'controller'	=>	'<controller>',
		'views'			=>	'<controller> [views]'
	);
	
	public function controller($controller)
	{	
		$name = ucwords($controller) .'Controller';
		
		$this->get('file')->remove("app/controllers/$name.php");		
		$this->views($controller);
	}
	
	public function views($controller, $views = array())
	{
		if(empty($views))
			return $this->get('dir')->remove("app/views/$controller/");
		
		else
			foreach($views as $view)
				$this->view($controller, $view);
	}
	
	private function view($controller, $view)
	{
		$this->get('file')->remove('app/views/'.$controller.'/'.$view.'.html.php');
	}
}
