<?php

// if ( ! defined('BASEURL')) exit('No direct access to this page');


function autoloader($class_name)
{
	//array of directories
	$directories						= array(
		'classes/',
		'../classes/',
		'../../classes/',
		'lib/',
		'../lib/',
		'../../lib/',
	);

	
	//array of filename formats
	$file_name_formats					= array(
		'%s.php'
	);
	
	// Iterate through directories
	foreach($directories as $directory)
	{
		foreach($file_name_formats as $file_name_format)
		{
			$file_path					= $directory.sprintf($file_name_format,$class_name);
			//echo $file_path."<br>";
			if(file_exists($file_path))
			{
				//echo '<br><br>this there => '.$file_path;
				require_once $file_path;
				return;
			}
		}
	}
	
}
	
	//Register the autoloader function
	spl_autoload_register('autoloader');
?>