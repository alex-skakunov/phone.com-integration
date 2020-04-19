<?php
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

function sendFailEmail($mess) {
    $message = new Message();
    $message->addTo(SEND_REPORTS_TO);
    $message->addFrom('Service Report <service.reports.mail@gmail.com>');
    $message->setSubject('An error has occured');
    $message->setBody($mess);

    $transport = new SmtpTransport();
    $options   = new SmtpOptions([
        'name'              => 'gmail.com',
        'host'              => 'smtp.gmail.com',
        'port'              => 587,
        // Notice port change for TLS is 587
        'connection_class'  => 'plain',
        'connection_config' => [
            'username' => REPORTS_SMTP_USERNAME,
            'password' => REPORTS_SMTP_PASSWORD,
            'ssl'      => 'tls',
        ],
    ]);
    $transport->setOptions($options);
    return $transport->send($message);
}

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