<?php

$root = __DIR__."/../";

set_include_path(
	$root.PATH_SEPARATOR.
	$root."../../src/".PATH_SEPARATOR.
	$root."../../vendor/".PATH_SEPARATOR
);

require "Midori\Autoload.php";
require $root."config/boot.php";