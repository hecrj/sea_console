<?php

namespace Sea\Console\Components;

class Injector extends DynamicInjector
{
	
	protected $classes = array(
		'dir'			=>	'Sea\\Console\\Components\\FileSystem\\Dir',
		'file'			=>	'Sea\\Console\\Components\\FileSystem\\File',
		'filesys'		=>	'Sea\\Console\\Components\\FileSystem\\FileSystem',
		'options'		=>	'Sea\\Console\\Components\\Options',
		'output'		=>	'Sea\\Console\\Components\\Output',
		'shell'			=>	'Sea\\Console\\Components\\Shell\\Shell'
	);

	protected $dependencies = array(
		'dir'		=>	array('output', 'file'),
		'file'		=>	array('output'),
		'filesys'	=>	array('dir', 'file'),
		'output'	=>	array('options')
	);
	
	protected $shared = array('file', 'dir', 'options', 'output', 'shell');
}
