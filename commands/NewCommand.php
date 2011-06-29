<?php

# NewCommand class
class NewCommand extends Command
{
	
	protected $syntax = array(
		'project'		=>	'<path>',
		'controller'	=>	'<controller> [actions]',
		'views'			=>	'<view> [views]'
	);
	
	protected $cmd_options = array(
		'global'		=>	array('quiet' => 'q'),
		'project'		=>	array('remote' => 'r', 'no-git' => 'g')
	);
	
	protected $pregs = array(
		'global'		=>	'/^([a-z0-9]+)$/',
		'path'			=>	false
	);
	
	protected $cmd_outside = 'project';
	
	public function project($prj_path)
	{	
		$path = escapeshellarg($prj_path);
		
		if(Command::$options['r'])
		{	
			Output::working(
				'Creating new project remotely...',
				'Downloading last Sea project version...'
			);
			system(sprintf('git clone --quiet git://github.com/hector0193/sea_project.git %s', $path));
			
			Output::working('Initializing Sea core submodule...');
			system(sprintf('cd %s && git submodule --quiet init core', $path));
			
			Output::working('Downloading Sea core as project submodule...');
			system(sprintf('cd %s && git submodule --quiet update core &> /dev/null', $path));
		}
		else
		{
			Output::working(
				'Creating new project...',
				'Copying project data...'
			);
			system(sprintf('git clone --quiet %s %s', DIR_PROJECT, $path));
			
			Output::working('Initializing core submodule...');
			system(sprintf('cd %s && git submodule --quiet init core', $path));
			
			Output::working('Copying core submodule locally...');
			system(sprintf('cd %s && git clone --quiet %s core', $path, DIR_CORE));
			
			Output::working('Getting Sea core submodule url...');
			$core_url = exec(sprintf('cd %s && git config --get submodule.core.url', $path));
			
			Output::working('Setting remote URLs...');
			system(sprintf('cd %s && git remote set-url origin git://github.com/hector0193/sea_project.git && cd core && git remote set-url origin %s', $path, $core_url));
		}
		
		if(Command::$options['g'])
			Dir::remove($prj_path .'/.git/', true);
		
		Output::success('Project created successfully.');
	}
	
	public function controller($controller, $actions)
	{
		// Setting controller name
		$controller_name = ucwords($controller). 'Controller';
			
		// If actions are empty... Set index as default
		if(empty($actions))
			$actions = array('index');
		
		// Create controller
		File::create('Controller', 'app/controllers/', $controller_name, '.php', $actions);
		
		// Create views
		foreach($actions as $action)
			$this->view($controller, $action);
	}
	
	private function view($controller, $view)
	{	
		File::create('View', 'app/views/'.$controller.'/', $view, '.html.php');
	}
	
	public function views($controller, $views)
	{
		// Create every view
		foreach($views as $view)
			$this->view($controller, $view);
	}
	
}

?>