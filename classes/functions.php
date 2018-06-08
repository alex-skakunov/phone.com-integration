<?php

//checks that we have all modules we need or exit() will be called
function check_necessary_functions()
{ 
  for($i=0; $i < func_num_args(); $i++)
  {
    $func_name = func_get_arg($i);
    if( !function_exists($func_name) )
    {
      exit ( "Function [" . $func_name . "] is not accessable. Please check that correspondent PHP module is installed at your web-server." );
    }
  }
  return true;
}

function query($sql, $replacements=null) {
    global $db;
    $stmt = $db->prepare($sql);
    if (false === $stmt->execute($replacements)) {
      new dBug($sql);
      throw new Exception($stmt->errorInfo()[2], $stmt->errorInfo()[1]);
      
    }
    return $stmt;
}

function tokenize($name) {
  return str_replace(' ', '_', strtolower($name));
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}