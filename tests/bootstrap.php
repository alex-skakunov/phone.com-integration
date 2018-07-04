<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Kiev');

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

if ('' == trim(getenv('APPLICATION_ENV'))) {
    exit("Please set APPLICATION_ENV environment variable\n");
}

define('APPLICATION_ENV', getenv('APPLICATION_ENV') . '-testing');

//used in tests since "true" value is too error-prone
define('TEST_VALUE', 42);

$_SERVER['SERVER_NAME'] = 'http://my.server.com';

include "preload.php"; //this includes all necessary classes and configs
