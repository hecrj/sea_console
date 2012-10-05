<?php

namespace Console\Components;

class Injector extends DynamicInjector
{
	
	protected $classes = array(
		'arguments'		=>	'Console\\Components\\Arguments',
		'dir'			=>	'Console\\Components\\FileSystem\\Dir',
		'file'			=>	'Console\\Components\\FileSystem\\File',
		'filesys'		=>	'Console\\Components\\FileSystem\\FileSystem',
		'finder'		=>	'Console\\Components\\Finder',
		'loader'		=>	'Console\\Components\\Loader',
		'options'		=>	'Console\\Components\\Options',
		'output'		=>	'Console\\Components\\Output',
		'shell'			=>	'Console\\Components\\Shell\\Shell'
	);

	protected $dependencies = array(
		'dir'		=>	array('output', 'file'),
		'file'		=>	array('output'),
		'filesys'	=>	array('dir', 'file', 'loader'),
		'output'	=>	array('options')
	);
	
	protected $shared = array('arguments', 'file', 'finder', 'loader', 'dir', 'options', 'output', 'shell');
}
