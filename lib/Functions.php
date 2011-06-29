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

?>