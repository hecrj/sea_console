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
		echo	'Usage:'."\n".
				'  sea new PROJECT_PATH'."\n".
				"\n".
				'Options:'."\n";
	}
	
}

?>