<?php

if(defined("MIDORI_ENV"))
	$env = MIDORI_ENV;
else 
	$env = "development";

Midori\Application\Initializer::run($env, function($config){
	
	$config->dateFormat = 'n/j/Y g:i A T';
	$config->timezone = "";
	$config->updateUrls();
});