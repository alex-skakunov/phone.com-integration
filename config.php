<?php

set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);

ini_set("arg_separator.output", "&amp;");
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", 'logs/error.log');
ini_set('date.timezone', 'America/Los_Angeles');
date_default_timezone_set('America/Los_Angeles');

//database settings
define("DB_HOST"    , '127.0.0.1');
define("DB_LOGIN"   , 'root');
define("DB_PASSWORD", '');
define("DB_NAME"    , 'ani_route');

define('ACCOUNT_ID', 691163);
define('ACCESS_TOKEN', 'FhsDh235ILVOv9ezJcekPBQUION7bNR4ZrVggkon7Z6E1cYK');


define("CURRENT_DIR"  , getcwd() . DIRECTORY_SEPARATOR );   //stand-alone classes
define("CLASSES_DIR"  , CURRENT_DIR . 'classes' .  DIRECTORY_SEPARATOR);   //stand-alone classes
define("ACTIONS_DIR"  , CURRENT_DIR . 'actions' .  DIRECTORY_SEPARATOR);   //controllers processing sumbitted data and preparing output
define("LOGS_DIR",  CURRENT_DIR . 'logs' . DIRECTORY_SEPARATOR); //all uploaded files will be copied here so that they won't be deleted between requests

//contact group identificators. The values correspond to ENUM field in queue.
define("EXISTING_ROUTE",  'Existing route');
define("OVER_X_MINUTES",  'Over X minutes');
