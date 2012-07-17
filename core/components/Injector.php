<?php

namespace Core\Components;

class Injector extends DynamicInjector
{
	
	protected $classes = array(
		'dir'			=>	'Core\\Components\\FileSystem\\Dir',
		'file'			=>	'Core\\Components\\FileSystem\\File',
		'filesys'		=>	'Core\\Components\\FileSystem\\FileSystem',
		'options'		=>	'Core\\Components\\Options',
		'output'		=>	'Core\\Components\\Output',
		'shell'			=>	'Core\\Components\\Shell\\Shell'
	);

	protected $dependencies = array(
		'dir'		=>	array('output', 'file'),
		'file'		=>	array('output'),
		'filesys'	=>	array('dir', 'file'),
		'output'	=>	array('options')
	);
	
	protected $shared = array('file', 'dir', 'options', 'output', 'shell');
}
