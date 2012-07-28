<?php

namespace Sea\Console\Commands;

class RmCommand extends CommandAbstract
{
	
	protected $syntax = array(
		'controller'	=>	'<controller>',
		'views'			=>	'<controller> [views]'
	);
	
	public function controller($controller)
	{	
		$name = ucwords($controller) .'Controller';
		
		$this->get('filesys')
				->setPath('app/controllers/')
				->remove($name, '.php');
		
		$this->views($controller);
	}
	
	public function views($controller, $views = array())
	{
		if(empty($views))
			return $this->get('filesys')->setPath('app/views/'. $controller)->remove();
		
		foreach($views as $view)
			$this->view($controller, $view);
	}
	
	private function view($controller, $view)
	{
		$this->get('file')->remove('app/views/'.$controller.'/'.$view.'.html.php');
	}
}
