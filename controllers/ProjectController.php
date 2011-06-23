<?php

class ProjectController extends Controller
{
	
	public function _new($project_path)
	{
		if(empty($project_path))
			exit('Empty project path.'."\n");
		
		echo 'Creating directories...'."\n";
		system(sprintf('cp -r %s %s', DIR.'project/', escapeshellarg($project_path)));
		
		echo 'Downloading Sea framework into '. $project_path .'/lib...'."\n";
		system(sprintf('git clone git://github.com/hector0193/hectamvc.git %s/lib', escapeshellarg($project_path)));
		
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