<?php

namespace Sea\Console\Commands;

class UpdateCommand extends CommandAbstract
{
	protected $syntax = array(
		'console'	=> '',
		'project'	=> ''
	);

	protected $cmd_outside = 'console';

	public function console()
	{
		$output = $this->get('output');
		$shell = $this->get('shell');

		$output->working('Updating Sea console...');
		$shell->execute('git pull origin master', DIR);

		$output->working('Updating Sea project local copy...');
		$shell->execute('git submodule update project', DIR);

		$output->working('Updating Sea core local copy...');
		$shell->execute('git submodule update core', DIR . 'project');

		$output->success('Sea framework updated successfully!');
	}

	public function project()
	{
		$output = $this->get('output');
		$shell = $this->get('shell');

		$output->working('Updating current Sea project...');
		$shell->execute('git pull origin master');

		$output->working('Updating core submodule...');
		$shell->execute('git submodule update core');

		$output->success('Current Sea project updated successfully!');
	}
}