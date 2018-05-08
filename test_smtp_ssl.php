<?php
require_once 'module\Swift-5.0.0\lib\swift_required.php';
require_once 'include\conf.php';

echo '<b>merTens-Profile:</b><br/>' . PHP_EOL;
echo '<a href="?profil=mertens-25">merTens Default (Port 25 ohne Verschluesselung)</a><br/>' . PHP_EOL;
echo '<a href="?profil=mertens-25tsl">merTens Port 25  via tsl</a><br/>' . PHP_EOL;
echo '<a href="?profil=mertens-ssl">merTens Port 465 via ssl</a><br/>'   . PHP_EOL;
echo '<a href="?profil=mertens-tsl">merTens Port 587 via tsl</a><br/>'   . PHP_EOL;
echo '<br>' . PHP_EOL;
echo '<b>google-Profile:</b><br/>' . PHP_EOL;
echo '<a href="?profil=google-25">Google-Konto (Post 25 ohne Verschlüsselung -> schlaegt fehl !!!)><br/>' . PHP_EOL;
echo '<a href="?profil=google-25tsl">Port 25  via tsl</a><br/>'  . PHP_EOL;
echo '<a href="?profil=google-465ssl">Port 465 via ssl</a><br/>' . PHP_EOL;
echo '<a href="?profil=google-587tsl">Port 587 via tsl</a><br/>' . PHP_EOL;

$profil = (isset($_REQUEST['profil'])) ? $_REQUEST['profil'] : 'mertens';
$user   = (isset($_REQUEST['user']))   ? $_REQUEST['user']   : '';
$pass   = (isset($_REQUEST['pwd']))    ? $_REQUEST['pwd']    : '';

$authParams = array(
    'host'     => $MConf['smtp_server'], //'10.10.1.69',
    'port'     => $MConf['smtp_port'], //'25',
    'encrypt'  => '',
    'user'     => (!$user ? $MConf['smtp_auth_user'] : $user),
    'pass'     => (!$pwd  ? $MConf['smtp_auth_pass'] : $pass),
    'from'     => $MConf['smtp_from_addr'],
    'fromAlias'=> $MConf['smtp_from_name'],
    'to'       => 'o.kowalski@mertens.ag',
    'subject'  => 'Test Subject',
    'body'     => 'This is a test mail.',
);


switch($profil) {    
    case 'mertens':
    case 'mertens-25':
        break;

    case 'mertens-25tsl':
        $authParams['port']    = '25';
        $authParams['encrypt'] = 'tsl';
        break;

    case 'mertens-ssl':
        $authParams['port']    = '465';
        $authParams['encrypt'] = 'ssl';
        break;
    
    case 'mertens-tsl':
        $authParams['port']    = '587';
        $authParams['encrypt'] = 'tsl';
        break;
    
   

    default:
      // Use Default Params
      die('No valid Profile selected: ' . $profil . '!');    
}

$logParams = array();
foreach($authParams as $k => $v) $logParams[$k] = ($k != 'pass') ? $v : '*****';

echo '<pre>' . PHP_EOL;
echo 'Versuche Mailversand mit den Parametern: ' . PHP_EOL . print_r($logParams, 1) . PHP_EOL;

$transport = Swift_SmtpTransport::newInstance($authParams['host'], $authParams['port'], $authParams['encrypt'])
  ->setUsername($authParams['user'])
  ->setPassword($authParams['pass']);

$mailer = Swift_Mailer::newInstance($transport);

// To use the ArrayLogger
//$logger = new Swift_Plugins_Loggers_ArrayLogger();
//$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
// Dump the log contents
// NOTE: The EchoLogger dumps in realtime so dump() does nothing for it
//echo $logger->dump();

// Or to use the Echo Logger
$logger = new Swift_Plugins_Loggers_EchoLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$message = Swift_Message::newInstance($authParams['subject'])
  ->setFrom(array($authParams['from'] => $authParams['fromAlias']))
  ->setTo(array( $authParams['to'] ))
  ->setBody( $authParams['body'] );
//  ->attach(Swift_Attachment::fromPath('image1.jpg'));

try {
      echo '#' . __LINE__ . ' start sending mail' . PHP_EOL;
      $result = $mailer->send($message);
} catch(Exception $e) {
      if (empty($logger)) echo '#' . __LINE__ . ' Mail-Error ' . $e->getMessage() . '' . PHP_EOL;
}
