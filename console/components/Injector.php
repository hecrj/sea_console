<?php

namespace Sea\Console\Components;

class Injector extends DynamicInjector
{
	
	protected $classes = array(
		'arguments'		=>	'Sea\\Console\\Components\\Arguments',
		'dir'			=>	'Sea\\Console\\Components\\FileSystem\\Dir',
		'file'			=>	'Sea\\Console\\Components\\FileSystem\\File',
		'filesys'		=>	'Sea\\Console\\Components\\FileSystem\\FileSystem',
		'finder'		=>	'Sea\\Console\\Components\\Finder',
		'loader'		=>	'Sea\\Console\\Components\\Loader',
		'options'		=>	'Sea\\Console\\Components\\Options',
		'output'		=>	'Sea\\Console\\Components\\Output',
		'shell'			=>	'Sea\\Console\\Components\\Shell\\Shell'
	);

	protected $dependencies = array(
		'dir'		=>	array('output', 'file'),
		'file'		=>	array('output'),
		'filesys'	=>	array('dir', 'file', 'loader'),
		'output'	=>	array('options')
	);
	
	protected $shared = array('arguments', 'file', 'finder', 'loader', 'dir', 'options', 'output', 'shell');
}
