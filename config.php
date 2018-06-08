<?php
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);

ini_set("arg_separator.output", "&amp;");
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", 'logs/error.log');

//database settings
define("DB_HOST"    , 'localhost');
define("DB_LOGIN"   , 'root');
define("DB_PASSWORD", '');
define("DB_NAME"    , 'ani_route');

define('ACCOUNT_ID', 6);
define('ACCESS_TOKEN', 'FhsDh235');

define('EXTENSION_FROM', 1854207);
define('EXTENSION_TO'  , 1854208);



define("CURRENT_DIR"  , getcwd() . DIRECTORY_SEPARATOR );   //stand-alone classes
define("CLASSES_DIR"  , CURRENT_DIR . 'classes' .  DIRECTORY_SEPARATOR);   //stand-alone classes
define("ACTIONS_DIR"  , CURRENT_DIR . 'actions' .  DIRECTORY_SEPARATOR);   //controllers processing sumbitted data and preparing output
define("LOGS_DIR",  CURRENT_DIR . 'logs' . DIRECTORY_SEPARATOR); //all uploaded files will be copied here so that they won't be deleted between requests
