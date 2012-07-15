<?php

namespace Core\Commands;

class UpdateCommand extends CommandAbstract
{
	protected $syntax = array(
		'console'	=> ''
	);

	protected $cmd_outside = 'console';

	public function console()
	{
		$output = $this->get('output');

		$output->working('Updating Sea console...');
		system(sprintf('cd %s && git pull --quiet origin master &> /dev/null', DIR));

		$output->working('Updating Sea project local copy...');
		system(sprintf('cd %s && git submodule --quiet update project &> /dev/null', DIR));

		$output->working('Updating Sea core local copy...');
		system(sprintf('cd %s && git submodule --quiet update core &> /dev/null', DIR.'project'));

		$output->success('Sea framework updated successfully!');
	}
}