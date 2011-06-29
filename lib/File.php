<?php

class File
{	
	
	public static function create($template, $path, $name, $extension, Array $data = null)
	{	
		// Relative path
		$rel_path = $path . $name . $extension;
		
		// Full path
		$full_path = DIR_WORKING . $rel_path;
		
		if(! file_exists(DIR_WORKING . $path))
			ExceptionUnless(@mkdir(DIR_WORKING . $path, 0755, true), 'Impossible to create directory: '. $path);
			
		elseif(file_exists($full_path))
			return Output::skipped($rel_path);
		
		ob_start();
		
		require(DIR . 'templates/'. $template .'.php');
		
		$code = ob_get_contents();
		
		ob_end_clean();
		
		if(!@touch($full_path) or !@file_put_contents($full_path, $code) !== FALSE)
			return Output::failed($rel_path);
		
		return Output::created($rel_path);
	}
	
	public static function remove($path, $rmDir = false)
	{	
		// Full path
		$full_path = DIR_WORKING . $path;
		
		if(! is_file($full_path))
			return Output::missing($path);
		
		ExceptionUnless(@unlink($full_path), 'Impossible to delete file: '. $path);
		
		Output::removed($path);
		
		if($rmDir)
		{
			$file_dir = dirname($path);
			
			if(@rmdir($file_dir))
				Output::removed(substr($file_dir, strlen(WORKING_DIR)));
		}
	}
	
}

?>