<?php

class HelpCommand extends Command
{
	protected $syntax = array(
		'project'	=>	'',
	);
	
	protected $cmd_options = array(
		'global'	=>	array('version' => 'v')
	);
	
	protected $cmd_outside = 'project';
	
	public function project()
	{	
		if(Command::$options['v'])
		{
			require(DIR . 'version.php');
			require(DIR_CORE . 'version.php');
			
			Output::text(
				'Local versions:',
				'  Sea console   '. Console\MAJOR .'.'. Console\MINOR .'.'. Console\TINY .' '. Console\PRE,
				'  Sea core      '. Core\MAJOR .'.'. Core\MINOR .'.'. Core\TINY .' '. Core\PRE
			);
			
			exit;
		}
		
		Output::text(
			'Usage:',
			'  sea new PROJECT_PATH [options]',
			'',
			'Options:',
			'  -r, [--remote]  # Gets remotely the last version available.',
			'',
			'Runtime options:',
			'  -q, [--quiet]   # Outputs error messages only.',
			'',
			'Description:',
			'    Creates a new Sea project based in the local copy. It is possible to create one',
			'    remotely getting the last Sea project version available.'
		);
	}
	
}

?>