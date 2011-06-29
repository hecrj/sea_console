<?php

class Dir
{
	
	public static function remove($path, $root = false)
	{
		if(substr($path, -1) != '/')
			$path .= '/';
		
		if(! is_dir($path))
		{
			if(is_file($path))
				return File::remove($path, $root);
			else
				return output('    '. yellow('invalid') .'    '. $path);
		}
		
		$objects = scandir(DIR_WORKING . $path); 
			
		foreach($objects as $object)
		{
			if($object == '.' or $object == '..')
				continue;
			
			if(is_dir(DIR_WORKING . $path . $object))
				self::remove($path . $object, true);

			else
				File::remove($path . $object);
		}
		
		if($root and @rmdir(DIR_WORKING . $path))
			output('    '. red('removed') .'    '. $path);
	}
	
}

?>