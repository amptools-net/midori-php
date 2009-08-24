<?php


desc("lists all the tasks");
task("-T", null, function(){
	$application = \Tab\Tab::getInstance()->application;
	$tasks = $application->tasks;
	usort($tasks, function($a, $b) {
		return strcmp($a->name,$b->name);
	});

	echo "\n  ".str_pad("Tasks", 40)."Description\n";
	foreach($tasks as $task)
	{
		echo "  ".str_pad($task->name, 40).$task->description."\n";		
	}
});

desc("gives the version");
task("-v", null, function(){
	echo "tab-php: v1 alpha";
});