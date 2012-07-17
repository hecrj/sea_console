<?php

namespace Core\Commands;

class HelpCommand extends CommandAbstract
{
	protected $syntax = array(
		'project'	=>	false,
	);
	
	protected $cmd_outside = 'project';
	
	public function project()
	{
		$output = $this->get('output');
		$options = $this->get('options');
		
		if($options->is('v', 'version'))
		{
			require(DIR . 'version.php');
			require(DIR_CORE . 'version.php');
			
			$output->text(
				'Local versions:',
				'  Sea console   '. \Console\MAJOR .'.'. \Console\MINOR .'.'. \Console\TINY .' '. \Console\PRE,
				'  Sea core      '. \Core\MAJOR .'.'. \Core\MINOR .'.'. \Core\TINY .' '. \Core\PRE
			);
			
			exit;
		}
		
		$output->text(
			'Usage:',
			'  sea new PROJECT_PATH [options]',
			'',
			'Options:',
			'  -r, [--remote]  # Gets remotely the last version available.',
			'  -g, [--no-git]  # Deletes main git repository after copying files.',
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
