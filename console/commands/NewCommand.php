<?php

namespace Console\Commands;

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
			sprintf('git clone %s %s', \Console\Repos\PROJECT, $path)
		);
		
		$output->working('Initializing Sea core submodule...');
		$shell->execute('git submodule init sea', $path);
		
		$output->working('Downloading Sea core as project submodule...');
		$shell->execute('git submodule update sea', $path);
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
			sprintf('git clone %s %s', \Console\DIR.'project', $path)
		);
			
		$output->working('Initializing core submodule...');
		$shell->execute('git submodule init sea', $path);
			
		$output->working('Copying core submodule locally...');
		$shell->execute(
			sprintf('git clone %s sea', \Console\DIR.'project/sea'),
			$path
		);
		
		$output->working('Setting remote URLs...');
		$shell->execute(
			sprintf('git remote set-url origin %s', \Console\Repos\PROJECT),
			$path
		);
		$shell->execute(
			sprintf('git remote set-url origin %s', \Console\Repos\CORE),
			$path.'/core'
		);
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
