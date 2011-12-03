<?php

namespace Core\Commands;

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
			$this->get('dir')->setPath($path .'/.git/')->remove();
		
		$this->get('output')->success('Project created successfully.');
	}
	
	private function createProjectRemotely($path)
	{
		$output = $this->get('output');
		
		$output->working(
			'Creating new project remotely...',
			'Downloading last Sea project version...'
		);
		system(sprintf('git clone --quiet git://github.com/hector0193/sea_project.git %s', $path));
		
		$output->working('Initializing Sea core submodule...');
		system(sprintf('cd %s && git submodule --quiet init core', $path));
		
		$output->working('Downloading Sea core as project submodule...');
		system(sprintf('cd %s && git submodule --quiet update core &> /dev/null', $path));
	}
	
	private function createProjectLocally($path)
	{
		$output = $this->get('output');
		
		$output->working(
			'Creating new project...',
			'Copying project data...'
		);
		system(sprintf('git clone --quiet %s %s', DIR_PROJECT, $path));
			
		$output->working('Initializing core submodule...');
		system(sprintf('cd %s && git submodule --quiet init core', $path));
			
		$output->working('Copying core submodule locally...');
		system(sprintf('cd %s && git clone --quiet %s core', $path, DIR_CORE));
			
		$output->working('Getting Sea core submodule url...');
		$core_url = exec(sprintf('cd %s && git config --get submodule.core.url', $path));
		
		$output->working('Setting remote URLs...');
		system(sprintf('cd %s && git remote set-url origin git://github.com/hector0193/sea_project.git && cd core && '.
				'git remote set-url origin git://github.com/hector0193/sea_core.git', $path));
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
