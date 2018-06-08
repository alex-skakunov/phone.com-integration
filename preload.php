<?php

include "config.php"; //load database settings, folders paths and such stuff

require_once 'vendor/autoload.php';

set_include_path( CLASSES_DIR );
require_once "functions.php";
require_once "dBug.php";
require_once "AbstractCall.php"; 
require_once "CallLog.php";
require_once "Extension.php";
require_once "Listener.php";


if( !is_writable( LOGS_DIR ) )
{
  exit ( "Temporary folder must be writable: <code>".LOGS_DIR."</code>" );
}

//connect to database
$dsn = sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME);
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
); 
$db = new PDO($dsn, DB_LOGIN, DB_PASSWORD, $options);

if(empty($db))
{
  exit("Cannot connect to database");
}

ini_set('auto_detect_line_endings', 1);