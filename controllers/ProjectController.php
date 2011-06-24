<?php

class ProjectController extends Controller
{
	
	public function _new($path)
	{
		if(empty($path))
			exit('Empty project path.'."\n");
		
		echo 'Creating new project...'."\n";
		
		system(sprintf('git clone --recursive git://github.com/hector0193/sea_project.git %s', escapeshellarg($path)));
		
		echo 'Project created successfully.'."\n";
	}
	
	public function help()
	{
		if(Handler::$options['v'] or Handler::$options['version'])
		{
			require(DIR . 'version.php');
			require(DIR_CORE . 'version.php');
			
			exit(
				'Sea console   '. Project\MAJOR .'.'. Project\MINOR .'.'. Project\TINY .' '. Project\PRE ."\n".
				'Sea core'. Core\MAJOR .'.'. Core\MINOR .'.'. Core\TINY .' '. Core\PRE ."\n"
			);
		}
		
		echo	'Usage:'."\n".
				'  sea new PROJECT_PATH'."\n".
				"\n".
				'Options:'."\n";
				
		print_r(Handler::$options);
	}
	
}

?>