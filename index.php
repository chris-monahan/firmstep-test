<?php

namespace QueueApp;

//uncomment to view warnings
error_reporting(E_ERROR | E_PARSE);


function requireAll($dirName, $onceLimit = true){
	$dir = new \DirectoryIterator($dirName);
	foreach ($dir as $fileinfo) {
		
		if 	((!$fileinfo->isDot()) && 
			($fileinfo->isReadable()) && 
			($fileinfo->isFile()) ) {
				
			$fullFileName = $fileinfo->getPathname();#
			if(substr($fullFileName, -4) === ".php"){
				if($onceLimit === true){
					require_once($fullFileName);
				}else{
					require($fullFileName);
				}
			}
		}
	}
}

requireAll(".");

$ourRequest = new Request();
$ourResponse = $ourRequest->handleAndGetResponse();

$ourResponse->serve();