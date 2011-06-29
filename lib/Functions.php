<?php

function ExceptionIf($boolean, $message)
{
	if($boolean)
		throw new Exception($message);
}

function ExceptionUnless($boolean, $message)
{
	if(!$boolean)
		throw new Exception($message);
}

function output()
{
	if(Command::$options['q'])
		return false;
	
	$outputs = func_get_args();
	
	foreach($outputs as $output)
	{
		
		echo $output ."\n";
	}
}

function white($text)
{
	return "\033[1;37m". $text . "\033[0m";
}

function green($text)
{
	return "\033[1;32m". $text . "\033[0m";
}

function red($text)
{
	return "\033[1;31m". $text . "\033[0m";
}

function blue($text)
{
	return "\033[1;34m". $text . "\033[0m";
}

function cyan($text)
{
	return "\033[1;36m". $text . "\033[0m";
}

function yellow($text)
{
	return "\033[1;33m". $text . "\033[0m";
}

?>