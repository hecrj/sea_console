<?php

namespace Console\Commands;

class InstallCommand extends CommandAbstract
{
	
	protected $syntax = array(
		'console' => ''
	);
	
	protected $cmd_outside = 'console';
	
	public function console()
	{
		$output = $this->get('output');
		$shell = $this->get('shell');

		$output->working('Detecting Operative System...');

		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			$output->failure('Sea console is not compatible with Windows yet.');
		else
		{
			$output->success('Operative System compatible!');

			if(file_exists('/usr/bin/sea'))
				$output->skipped('Sea console is already installed!');
			else
			{
				$output->working('Initializing and updating project submodule...');
				$shell->execute('git submodule update --init project', \Console\DIR);

				$output->working('Initializing and updating core submodule...');
				$shell->execute('git submodule update --init core', \Console\DIR.'project');

				$output->working('Installing Sea framework console...');

				$this->get('filesys')
					->setPath('/usr/bin/')
					->set('consoleDir', \Console\DIR)
					->generate('BashScript', 'sea', '');

				$output->working('Setting executable permissions...');
				chmod('/usr/bin/sea', 0755);

				$output->success('Sea framework console installed successfully!');
			}
		}
	}
	
}
