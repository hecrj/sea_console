<?php

namespace Sea\Console\Commands;

class NewCommand extends CommandAbstract
{
	
	protected $syntax = array(
		'project'		=>	'<path>',
		'controller'	=>	'<controller> [actions]',
		'views'			=>	'<view> [views]'
	);
	
	protected $cmd_outside = 'project';
	
	public function project($path)
	{
		$options = $this->get('options');
		
		if($options->is('r', 'remotely'))
			$this->createProjectRemotely($path);
		else
			$this->createProjectLocally($path);
		
		if($options->is('g', 'no-git'))
			$this->get('dir')->remove("$path/.git/");
		
		$this->get('output')->success('Project created successfully.');
	}
	
	private function createProjectRemotely($path)
	{
		$output = $this->get('output');
		$shell = $this->get('shell');
		
		$output->working(
			'Creating new project remotely...',
			'Downloading last Sea project version...'
		);

		$shell->execute(
			sprintf('git clone git://github.com/hector0193/sea_project.git %s', $path)
		);
		
		$output->working('Initializing Sea core submodule...');
		$shell->execute('git submodule init core', $path);
		
		$output->working('Downloading Sea core as project submodule...');
		$shell->execute('git submodule update core', $path);
	}
	
	private function createProjectLocally($path)
	{
		$output = $this->get('output');
		$shell = $this->get('shell');
		
		$output->working(
			'Creating new project...',
			'Copying project data...'
		);

		$shell->execute(
			sprintf('git clone %s %s', DIR_PROJECT, $path)
		);
			
		$output->working('Initializing core submodule...');
		$shell->execute('git submodule init core', $path);
			
		$output->working('Copying core submodule locally...');
		$shell->execute(
			sprintf('git clone %s core', DIR_CORE),
			$path
		);
		
		$output->working('Setting remote URLs...');
		$shell->execute('git remote set-url origin git://github.com/hector0193/sea_project.git', $path);
		$shell->execute('git remote set-url origin git://github.com/hector0193/sea_core.git', $path.'/core');
	}
	
	public function controller($controller, $actions)
	{
		$controllerName = ucfirst($controller). 'Controller';
		
		if(empty($actions))
			$actions = array('index');
		
		$this->get('filesys')
				->setPath('app/controllers/')
				->set('name', $controllerName)
				->set('actions', $actions)
				->generate('Controller', $controllerName);
		
		$this->views($controller, $actions);
	}
	
	private function view($controller, $view)
	{	
		$this->get('filesys')
				->setPath('app/views/'. $controller .'/')
				->generate('View', $view, '.html.php');
	}
	
	public function views($controller, $views)
	{
		// Create every view
		foreach($views as $view)
			$this->view($controller, $view);
	}
	
}
