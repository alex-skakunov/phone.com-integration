<?php
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);

ini_set("arg_separator.output", "&amp;");
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", 'logs/error.log');
ini_set('auto_detect_line_endings', 1);


define("CURRENT_DIR"  , getcwd() . DIRECTORY_SEPARATOR );   //stand-alone classes
define("CLASSES_DIR"  , CURRENT_DIR . 'classes' .  DIRECTORY_SEPARATOR);   //stand-alone classes
define("ACTIONS_DIR"  , CURRENT_DIR . 'actions' .  DIRECTORY_SEPARATOR);   //controllers processing sumbitted data and preparing output
define("TEMP_DIR",  CURRENT_DIR . 'temp' . DIRECTORY_SEPARATOR); //all uploaded files will be copied here so that they won't be deleted between requests
define("SESSIONS_DIR", CURRENT_DIR . 'temp' . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR); //sessions are stored here
define('SESSION_TTL', 60 * 60 * 24 * 120); //120 days

//contact group identificators. The values correspond to ENUM field in queue.
define("EXISTING_ROUTE",  'Existing route');
define("OVER_X_MINUTES",  'Over X minutes');

include "config.php"; //load database settings, folders paths and such stuff

require_once 'vendor/autoload.php';

session_save_path(rtrim(SESSIONS_DIR, '/'));
session_start();
setcookie(session_name(),session_id(),time() + SESSION_TTL, "/");

set_include_path( CLASSES_DIR );
require_once "functions.php";
require_once "dBug.php";
require_once "AbstractCall.php"; 
require_once "CallLog.php";
require_once "Extension.php";
require_once "Listener.php";
require_once "Queue.php";


//connect to database
$dsn = sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME);
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
); 
$db = new PDO($dsn, DB_LOGIN, DB_PASSWORD, $options);

!empty($db) || exit("Cannot connect to database");