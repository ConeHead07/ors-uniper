<?php
require_once __DIR__ . '/../module/Swift-5.0.0/lib/swift_required.php';
if ( basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
    require __DIR__ . '/../include/conf.php';
    define('SMTP_MAILER_DEBUG', 1);
}

!defined('SMTP_MAILER_DEBUG') || define('SMTP_MAILER_DEBUG', APP_ENVIRONMENT === 'DEVELOPMENT' ? 1 : 0);
define('SMTP_MAILER_REALSEND', 1);
$smtpSender = 'default';
$aValidDebugSender = [
    'default',
    'bayerors',
    'bcsors',
    'dussmannors',
    'vodafoneors',
    'gmail',
    'projectgmail',
];

if (!empty($_REQUEST['sender']) && $_REQUEST['sender'] !== 'default' ) {
    if (in_array($_REQUEST['sender'], $aValidDebugSender)) {
        $smtpSender = $_REQUEST['sender'];
    } else {
        die('INVALID COMFIG-NAME FOR SENDER: ' . $_REQUEST['sender']);
    }
}


$aSmtpConn = array(
    "server"    => $MConf['smtp_server'], //"10.10.1.70",
    "port"      => $MConf['smtp_port'], //"25",
    "encrypt"   => 'tls',
    "helofrom"  => $MConf['smtp_client_host'], //getenv('HTTP_HOST'),
    "from_name" => $MConf['smtp_from_name'],
    "from_addr" => $MConf['smtp_from_addr'],
    "from"      => '"'.$MConf['smtp_from_name'] . '" <' . $MConf['smtp_from_addr'].'>',
    "postfach_from" => '<' . $MConf['smtp_from_addr'].'>',
    'helofrom' => gethostbyname(gethostname()),
    "auth_user" => $MConf['smtp_auth_user'],
    "auth_pass" => $MConf['smtp_auth_pass'],
    "socket"    => "",
    "connection_timeout" => 5,
    "timeIn"    => time(),
    "antwort"   => "",
    "logsmtp"   => 1,
    "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".txt",
    "tat"       => "" // Transaktionstext mit SERVER
);

if(SMTP_MAILER_DEBUG > 0 && $smtpSender === 'gmail') {
    $aSmtpConn = array_merge($aSmtpConn, array(
            "server"    => 'smtp.gmail.com',
            "port"      => 25,
            "encrypt"   => 'tls',
            "from_name" => 'Mertens ORS Uniper',
            "from_addr" => 'mertens.ors.uniper@gmail.com',
            "from"      => '"Mertens ORS Uniper" <mertens.ors.uniper@gmail.com>',
            "postfach_from" => '<mertens.ors.uniper@gmail.com>',
            "auth_user" => 'mertens.ors.uniper@gmail.com',
            "auth_pass" => 'aDjSoNHQQKzT7',
            "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
        ));
}

if (SMTP_MAILER_DEBUG > 0 && $smtpSender === 'bayerors') {
    $aSmtpConn = array_merge($aSmtpConn, array(
        'server'    => "mail.mertens.ag",
        'port'      => "25",
        'encrypt'   => 'tls',
        'from_name' => 'Order Request System',
        'from_addr' => 'bayerors@mertens.ag',
        'from' => '"Order Request System" <bayerors@mertens.ag>',
        'postfach_from' => '<bayerors@mertens.ag>',
        'auth_user' => 'mag\bayermove',
        'auth_pass' => 'merTens47877',
        'helofrom' => 'bayer.mertens.ag',
        "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
    ));
}

if (SMTP_MAILER_DEBUG > 0 && $smtpSender === 'bcsors') {
    $aSmtpConn = array_merge($aSmtpConn, array(
        "server"    => "mail.mertens.ag",
        "port"      => "25",
        'encrypt'   => 'tls',
        'from_name' => 'Order Request System',
        'from_addr' => 'bcsors@mertens.ag',
        'from' => '"Order Request System" <bcsors@mertens.ag>',
        'postfach_from' => '<bcsors@mertens.ag>',
        'auth_user' => 'mag\bcsmove',
        'auth_pass' => 'merTens47877',
        'helofrom' => 'bcs.mertens.ag',
        "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
    ));
}

if (SMTP_MAILER_DEBUG > 0 && $smtpSender === 'dussmannors') {
    $aSmtpConn = array_merge($aSmtpConn, array(
        "smtp_server"    => 'mail.mertens.ag', // '10.30.2.100', '10.30.2.101' "10.10.1.69",
        "smtp_port"      => "25",
        'encrypt'   => 'tls',
        'from_name' => 'Order Request System',
        'from_addr' => 'ors@mertens-henk.de',
        'from' => '"Order Request System" <ors@mertens-henk.de>',
        'postfach_from' => '<ors@mertens-henk.de>',
        'auth_user' => 'mag\ors', // Alter Eintrag: "wp154616-bewerbung",
        'auth_pass' => 'Mh#14!',
        'helofrom' => 'dsd.mertens-henk.de',
        "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
    ));
}

if (SMTP_MAILER_DEBUG > 0 && $smtpSender === 'vodafoneors') {
    $aSmtpConn = array_merge($aSmtpConn, array(
        "server"    => "mail.mertens.ag",
        "port"      => "25",
        'encrypt'   => 'tls',
        'from_name' => 'Order Request System',
        'from_addr' => 'move@mertens.ag',
        'from' => '"Order Request System" <move@mertens.ag>',
        'postfach_from' => '<move@mertens.ag>',
        'auth_user' => 'mag\move',
        'auth_pass' => 'move2010',
        'helofrom' => 'vodafone.mertens.ag',
        "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
    ));
}



if((SMTP_MAILER_DEBUG > 0 && $smtpSender === 'projectgmail')) {
    $aSmtpConn = array_merge($aSmtpConn, array(
        "server"    => 'smtp.gmail.com',
        "port"      => 25,
        "encrypt"   => 'tls',
        "from_name" => 'Mertens Projekt-Tickets',
        "from_addr" => 'mertens.openproject@gmail.com',
        "from"      => '"Mertens Projekt-Tickets" <mertens.openproject@gmail.com>',
        "postfach_from" => '<mertens.openproject@gmail.com>',
        "auth_user" => 'mertens.openproject@gmail.com',
        "auth_pass" => 'CersrDzC4b',
        "logfile"   => __DIR__ . "/../log/log_smtp_".date("YmdHis").".$smtpSender.txt",
    ));
}

if(SMTP_MAILER_DEBUG > 0 ) {
    echo 'smtpSender: ' . $smtpSender . "<br>\n";
    echo '| ';
    foreach( $aValidDebugSender as $_sender) {
        echo '<a href="?sender=' . $_sender . '">' . $_sender . '</a> | ';
    }
    echo '<pre>' . htmlentities(json_encode($aSmtpConn, JSON_PRETTY_PRINT)) . '</pre>';
}

$aSmtpDebugTo = [
    [
        'email' => 'frank.barthold@gmail.com',
        'anrede' => 'Herr Barthold',
    ],
    [
        'email' => 'ors-service@mertens.ag',
        'anrede' => 'ORS Service',
    ],
    [
        'email' => 'f.barthold@mertens.ag',
        'anrede' => 'Herr Barthold',
    ],
/*
	[
		'email' => 'o.kowalski@mertens.ag',
		'anrede' => 'Herr Kowalski',
	],
	[
		'email' => 'd.koenig@mertens.ag',
		'anrede' => 'Herr Koenig',
	],
*/
];

$aHeader = array(
    // "From"        => $aSmtpConn['from'],
    "Reply-To"    => $aSmtpConn['from_addr'],
    "Errors-To"   => $aSmtpConn['from_addr'],
    "BCC"         => 'f.barthold@mertens.ag',
    'Return-Path' => $aSmtpConn['from_addr'],
    'Bounce-To'   => $aSmtpConn['from_addr'],
    "multipart_data" => "",
	'X-LINES' => '#' . __LINE__,
);
$LINE = __LINE__;
if (0) die(
    json_encode(compact('LINE', 'aSmtpConn', 'aHeader'), JSON_PRETTY_PRINT)
);

class SmtpMailer {

    var $aSmtpConn = array();
    var $log = '';
    var $logfile = '';
    var $server = '';
    var $from_name = '';
    var $from_addr = '';
    var $encrypt = '';
    var $socket = '';
    var $logsmtp = '';
    var $timeIn = '';
    var $tat = '';
    var $command = '';
    var $antwort = "";
    var $port = '';
    var $helofrom = '';
    var $postfach_from = '';
    var $auth_user = '';
    var $auth_pass = '';
    var $limitOffset = ''; // Formular-Duchreiche-Wert in stichtag_test.php
    var $connection_timeout = 10;
    var $constHeader = array();
    var $lastHeaders = array();

    /** @var Swift_SmtpTransport  */
    protected $transport = null;

    /** @var Swift_Mailer  */
    protected $mailer = null;

    /** @var Swift_Message  */
    protected $message = null;

    /** @var array  */
    protected $headers = [];

    /** @var array of Swift_Plugins_Logger  */
    protected $logger = [];

    /** @var array  */
    protected $attachments = [];

    /** @var array  */
    protected $to = [];

    /** @var string  */
    protected $subject = "";

    /** @var string  */
    protected $body = "";
    /** @var string  */
    protected $textBody = "";
    /** @var string  */
    protected $htmlBody = "";

    /** @var array  */
    protected $tplVars = [];

    protected $isHtmlAndText = false;

    /**
     * SmtpMailer constructor.
     * @param $aSmtpConn
     */
    function __construct($aSmtpConn) {
        if (isset($aSmtpConn["from_name"]))       $this->from_name =     $aSmtpConn["from_name"];
        if (isset($aSmtpConn["from_addr"]))       $this->from_addr =     $aSmtpConn["from_addr"];
        if (isset($aSmtpConn["encrypt"]))         $this->encrypt =       $aSmtpConn["encrypt"];
        if (isset($aSmtpConn["log"]))             $this->log =           $aSmtpConn["log"];
        if (isset($aSmtpConn["logfile"]))         $this->logfile =       $aSmtpConn["logfile"];
        if (isset($aSmtpConn["server"]))          $this->server =        $aSmtpConn["server"];
        if (isset($aSmtpConn["socket"]))          $this->socket =        $aSmtpConn["socket"];
        if (isset($aSmtpConn["logsmtp"]))         $this->logsmtp =       $aSmtpConn["logsmtp"];
        if (isset($aSmtpConn["timeIn"]))          $this->timeIn =        $aSmtpConn["timeIn"];
        if (isset($aSmtpConn["tat"]))             $this->tat =           $aSmtpConn["tat"];
        if (isset($aSmtpConn["antwort"]))         $this->antwort =       $aSmtpConn["antwort"];
        if (isset($aSmtpConn["port"]))            $this->port =          $aSmtpConn["port"];
        if (isset($aSmtpConn["helofrom"]))        $this->helofrom =      $aSmtpConn["helofrom"];
        if (isset($aSmtpConn["postfach_from"]))   $this->postfach_from = $aSmtpConn["postfach_from"];
        if (isset($aSmtpConn["auth_user"]))       $this->auth_user =     $aSmtpConn["auth_user"];
        if (isset($aSmtpConn["auth_pass"]))       $this->auth_pass =     $aSmtpConn["auth_pass"];
        if (isset($aSmtpConn["limitOffset"]))     $this->limitOffset =   $aSmtpConn["limitOffset"]; // Formular-Duchreiche-Wert in stichtag_test.php
        if (isset($aSmtpConn["connection_timeout"]))     $this->connection_timeout =     $aSmtpConn["connection_timeout"]; // Formular-Duchreiche-Wert in stichtag_test.php

        $this->constHeader = array(
        );
    }

    public static function mimeHeaderTxtToArray($sHeader) {
        $aHeader = array();
        $aHLines = explode("\n", $sHeader);
        foreach($aHLines as $line) {
            $aLineParts = explode(':', $line,2);
            if (count($aLineParts) < 2) continue;
            $aHeader[trim($aLineParts[0])] = trim($aLineParts[1]);
        }
        return $aHeader;
    }

    /**
     * Check if aArray contains one of given ordered Key and return that value or Default
     *
     * @param array $aArray
     * @param array $aKeysOrdered
     * @param $default
     * @return mixed
     */
    private function getArrayElm(array $aArray, array $aKeysOrdered, $default = null) {
        $aKeysOrdered = (array) $aKeysOrdered;
        foreach($aKeysOrdered as $key) {
            if (isset($aArray[$key])) return $aArray[$key];
        }
        return $default;
    }

    /**
     * Factory ruft constructor mit globalen smtp-conf-daten auf
     *
     * @return SmtpMailer
     */
    public static function getNewInstance() {
        global $aSmtpConn;
        return new self($aSmtpConn);
    }

    /**
     * Assign Tpl-Var for Replacing Placeholder in Subject, textBody and htmlBody
     *
     * @param string $key
     * @param string|null $val
     * @return $this
     */
    public function assign($key, $val = null, $charset = 'ISO-8859-1') {
        if (is_array($key)) {
            foreach($key as $_k => $_v) {
                $this->tplVars[ (string) $_k] = is_numeric($_v) ? $_v : $this->toUtf8((string) $_v, $charset);
            }
        } else {
            $this->tplVars[ $key] = is_numeric($val) ? $val : $this->toUtf8((string) $val, $charset);
        }
        return $this;
    }

    /**
     * Reset And Set all tpl-Vars for subject, textBody und htmlBody by given assoc-array
     *
     * @param array $aKeyValuePairs
     * @return $this
     */
    public function setTplVars(array $aKeyValuePairs, $charset = 'ISO-8859-1') {
        $this->tplVars = [];
        $this->assign($aKeyValuePairs, null, $charset);
        return $this;
    }

    public static function toUtf8(string $str, string $fromCharset = 'ISO-8859-1') {
        // $_detectedCS = mb_detect_encoding($str);
        if (!$fromCharset) {
            $fromCharset = mb_detect_encoding($str);
        }

        switch(strtoupper($fromCharset))
        {
            case 'UTF-8':
            case 'ASCII':
                return $str;

            case 'ISO-8859-1':
                return utf8_encode($str);

            case 'WINDOWS-1252':
                return mb_convert_encoding($str, 'UTF-8', 'Windows-1252');

            default:
                return $str;
        }
    }

    /**
     * @param array $aTo
     * @param $sSubject
     * @param null $sHtmlBody
     * @param null $sTxtBody
     * @param array $aAttachments
     * @param array $aUseHeaders
     * @return int number of accepted recipients
     */
    public function sendMultiMail(array $aTo, $sSubject, $sHtmlBody = null, $sTxtBody = null, array $aAttachments = [], array $aUseHeaders = [])
    {
        global $aHeader;
        global $aSmtpConn;
        global $aSmtpDebugTo;

        if (preg_match('#ID\s*(?P<id>\d+)\b#', $sSubject, $m)) {
            $this->logfile = str_replace("log_smtp", "log_".$m['id'].'_smtp', $this->logfile);
        }

        if (defined('SMTP_MAILER_DEBUG') && SMTP_MAILER_DEBUG === 1) {
            $aUseHeaders = $aHeader + $aUseHeaders;
        }

        if (isset($aUseHeaders['multipart_data'])) {
            unset($aUseHeaders['multipart_data']);
        }

        if (isset($aUseHeaders['BCC'])) {
		    $bcc = trim($aUseHeaders['BCC']);
            unset($aUseHeaders['BCC']);
        } else {
            $bcc = '';
        }

        if (isset($aUseHeaders['CC'])) {
		    $cc = trim($aUseHeaders['CC']);
            unset($aUseHeaders['CC']);
        } else {
            $cc = '';
        }

        if (defined('SMTP_MAILER_DEBUG') && SMTP_MAILER_DEBUG === 1) {

            if ($sHtmlBody) {
                $sHtmlBody .= "<br>\n<br>\n"
                    . str_repeat('*', 50)
                    . "<br>\nOriginal aTo: <pre>"
                    . print_r($aTo, 1)
                    . "</pre>";
            }

            if ($sTxtBody) {
                $sTxtBody.= "\n\n" . str_repeat('*', 50) . "\n Original aTo: " . print_r($aTo, 1);
            }

            $aTo = $aSmtpDebugTo;
        }
        $aUseHeaders['X-LINES'].= ',#' . __LINE__;
        file_put_contents(
            $this->logfile,
            print_r([
                'aTo'=> $aTo,
                'sSubject' => $sSubject,
                'line' => __LINE__,
                'sHtmlBody' =>  $sHtmlBody,
                'sTxtBody' => $sTxtBody,
                'aAttachments' => $aAttachments,
                'aHeader' =>  $aHeader,
                'aSmtpConn' => $aSmtpConn,
                'aUseHeaders' =>  $aUseHeaders,
                'rplVars' => $this->subject,
            ],1),
            FILE_APPEND
        );

        $this->createDefaultMailer();
        $this->addHeaders($aUseHeaders);
        $this->addLogger(new Swift_Plugins_Loggers_ArrayLogger());

        $numAcceptedRecipients = 0;

        foreach($aTo as $i => $_to) {

            $_to['email'] = trim($_to['email'], '<>');

            // Assign Tpl-Vars
            $this->assign('to.email', $_to['email']);
            $this->assign('to.anrede', $_to['anrede'] ?? '');

            $this->subject  = $this->renderTplVars( $sSubject, true );
            $this->textBody = $this->renderTplVars( $sTxtBody, false);
            $this->htmlBody = $this->renderTplVars( $sHtmlBody, true );

            $this->createMessage();
            $type = $this->message->getHeaders()->get('Content-Type');
            $type->setValue('text/plain');
            $type->setParameter('charset', 'iso-8859-1');
            $this->message->setTo($_to['email'], $_to['anrede'] ?? '');
            $this->message->setSubject($this->subject);

            if (!empty($bcc)) {
                $this->message->setBcc($bcc);
            }

            if (!empty($cc)) {
                $this->message->setCc($cc);
            }

            $this->isHtmlAndText = (!empty($sTxtBody) && !empty($sHtmlBody));

            if ($this->isHtmlAndText) {
                $this->message->addPart( $this->textBody, 'text/plain' );
                $this->message->addPart( $this->htmlBody, 'text/html' );
            } elseif ($this->htmlBody) {
                $this->message->setBody($this->htmlBody, 'text/html' );
            } else {
                $this->message->setBody($this->textBody, 'text/plain' );
            }

            $this->addHeader('X-QUEUE-Date', date("Y-m-d H:i:s"));
            $this->addAttachments($aAttachments);

            $error = '';
            $result = 0;
            try {
                $this->lastHeaders = [];
                $swiftMessageHeaders = $this->message->getHeaders();
                $aAllHeraderNames = $swiftMessageHeaders->listAll();
                foreach($aAllHeraderNames as $_headername) {
                    $_list = $swiftMessageHeaders->getAll($_headername);
                    foreach($_list as $_header) {
                        $this->lastHeaders[] = '[' . $_headername . '] ' . $_header . "\n";
                    }
                }
                if (0) echo '<pre>' . print_r([
                        'to' => $_to['email'],
                        'anrede' => $_to['anrede'] ?? '',
                        'subject' => $this->subject,
                        'textBody' => $this->textBody,
                        'htmlBody' => $this->htmlBody,
                        'swiftHeaders' => $this->lastHeaders,
                ], 1) . '</pre>' . "\n";
                $result = $this->send();

            } catch(Exception $e) {
                $error = $e->getMessage();
            }
            $numAcceptedRecipients += $result;

            try { throw new Exception('Get Stacktrace'); } catch(Exception $e) { $stackTrace = $e->getTraceAsString(); }

            file_put_contents(
                $this->logfile,
		    print_r(['_to' => $_to],1)
                . PHP_EOL . 'AUTH_USER: ' . $this->auth_user
                . PHP_EOL . 'AUTH_PASS: ' . $this->auth_pass
                . PHP_EOL . $this->logger[0]->dump()
                . PHP_EOL . 'Result (Num Accepted Recipients): ' . $result
                . PHP_EOL . 'Error : ' . $error
                . PHP_EOL . 'TRACE : ' . $stackTrace, FILE_APPEND)
                . PHP_EOL . 'HEADERS : ' . implode("\n", $this->lastHeaders);
        }

        return $numAcceptedRecipients;
    }

    /**
     * Add Attachments by indexed Array of structured Arrays
     * - Struct must specify the type (file or data) in the key quelle, source, or type
     * - the key file represents the Filename or the unencrypted Inline-Data
     * - additionally by Attachments on the fly it must specify the mimeType and filename (fname or name)
     *
     * @param array $aAttachments
     * @return $this
     * @throws Exception
     */
    public function addAttachments(array $aAttachments) {
        foreach($aAttachments as $_aItem) {

            $type = $this->getArrayElm($_aItem, ['quelle', 'source', 'type'], null);

            if (!is_array($_aItem) || empty($_aItem['quelle']) || empty($_aItem['file'])) {
                throw new Exception('Invalid Data-Structure of Attachment. Should be array with keys quelle, file!');
            }

            $mimeType = $this->getArrayElm($_aItem, ['fmime', 'mimeType'], null);
            $fName = $this->getArrayElm($_aItem, ['fname', 'name'], null);

            switch($type) {
                case 'data':
                    $this->addAttachmentByData($_aItem['file'], $fName, $mimeType);
                    break;

                case 'local':
                case 'file':
                    $this->addAttachmentByFile($_aItem['file'], $fName, $mimeType);
                    break;

                default:
                    throw new Exception( 'Invalid type of attachment. Should be data or local (file)!');
            }

        }
        return $this;
    }

    /**
     * @param string $sVal
     * @param bool $removeRemainingTplVars
     * @return string
     */
    private function renderTplVars($sVal, $removeRemainingTplVars = false) {
        $sReturn = $sVal;
        foreach($this->tplVars as $k => $v) {
            $sReturn = str_replace('{' . $k . '}', $v, $sReturn);
        }

        if ($removeRemainingTplVars) {
            $sReturn = $this->removeTplVars($sReturn);
        }
        return $sReturn;
    }

    /**
     * @param string $sVal
     * @return string mixed
     */
    private function removeTplVars($sVal) {
        $sReturn = preg_replace('/{.+}/s', '', $sVal);
        return $sReturn;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $mimeType
     * @param array $aHeaders
     * @return $this
     */
    public function createMail($to, $subject, $body, $mimeType, array $aHeaders = []) {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->createDefaultMailer()->createMessage($subject);
        $this->getMessage()
            ->setTo($to)
            ->setBody($body)
            ->setContentType($mimeType);
        $this->addHeaders($aHeaders);
        return $this;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $textBody
     * @param string $htmlBody
     * @param array $aHeaders
     * @return $this
     */
    public function createHtmlAltTextMail($to, $subject, $textBody, $htmlBody, array $aHeaders = []) {
        $this->to = $to;
        $this->subject  = $subject;
        $this->textBody = $textBody;
        $this->htmlBody = $htmlBody;
        $this->isHtmlAndText = true;
        $this->createDefaultMailer()->createMessage($subject);
        $this->getMessage()
            ->setTo($to)
            ->addPart($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html');

        $this->addHeaders($aHeaders);
        return $this;
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @param array $aHeaders
     * @return SmtpMailer
     */
    public function createTextMail($to, $subject, $body, array $aHeaders = []) {
        return $this->createMail($to, $subject, $body, 'text/plain', $aHeaders);
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @param array $aHeaders
     * @return int
     */
    public function sendTextMail($to, $subject, $body, array $aHeaders = []) {
        return $this->createTextMail($to, $subject, $body, $aHeaders)->send();
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @param array $aHeaders
     * @return SmtpMailer
     */
    public function createHtmlMail($to, $subject, $body, array $aHeaders = []) {
        return $this->createMail($to, $subject, $body, 'text/html', $aHeaders);
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @param $aHeaders
     * @return int
     */
    public function sendHtmlMail($to, $subject, $body, $aHeaders) {
        return $this->createHtmlMail($to, $subject, $body, $aHeaders)->send();
    }

    /**
     * Create SMTP-Transporter with configured Smtp-Server, -Port, -encryption, user and password
     *
     * @return $this
     */
    private function createTransporter() {
        $this->transport = Swift_SmtpTransport::newInstance(
            $this->server,
            $this->port,
            $this->encrypt
        )
            ->setUsername($this->auth_user)
            ->setPassword($this->auth_pass);
        $this->transport->setLocalDomain($this->helofrom);

        return $this;
    }

    /**
     * Get SMTP-Transporter
     *
     * @return Swift_SmtpTransport
     */
    public function getTransporter() {
        return $this->transport;
    }

    /**
     * Set Smtp-Transporter
     *
     * @param $transport
     */
    public function setTransporter($transport) {
        $this->transport = $transport;
    }

    /**
     * Create Mailer with Transporter, triggers createTransporter if transporter is not yet created
     *
     * @return $this
     */
    private function createDefaultMailer() {
        if (is_null($this->transport)) $this->createTransporter();
        $this->mailer = Swift_Mailer::newInstance($this->getTransporter());
        return $this;
    }

    /**
     * @return Swift_Mailer
     */
    private function getMailer() {
        return $this->mailer;
    }

    /**
     * @return Swift_Mailer
     */
    private function getDefaultMailer() {
        if ( is_null($this->mailer)) $this->createDefaultMailer();
        return $this->mailer;
    }

    /**
     * Add Logger for Debugging by enabling logging the conversation between SMTP-Client and -Server
     *
     * @param Swift_Plugins_Logger $logger
     * @return $this
     */
    public function addLogger(Swift_Plugins_Logger $logger) {
        $this->logger[] = new Swift_Plugins_LoggerPlugin($logger);
        return $this;
    }

    /**
     * Send Email with all Preparations, Plugins, Attachments and Headers
     *
     * @return integer number of accepted Recipients
     */
    public function send() {
        foreach($this->logger as $logger) {
            $this->mailer->registerPlugin( $logger );
        }

        foreach($this->attachments as $attachment) {
            $this->message->attach( $attachment );
        }

        $thisHeaders = $this->message->getHeaders();
        foreach($this->headers as $_k => $_v) {
            $thisHeaders->addTextHeader($_k, $_v);
        }

        if (APP_ENVIRONMENT === 'PRODUKTION' || SMTP_MAILER_REALSEND === 1) {
            $result = $this->mailer->send( $this->message );
        } else {
            $result = 2;
        }

        return $result;
    }

    /**
     * Create Message, which takes all infos like to, subject, mime-multiparts, attachements, headers
     *
     * @param $sSubject
     * @return $this
     */
    private function createMessage($sSubject = null) {
        $this->message = Swift_Message::newInstance();
        if (is_string($sSubject)) {
            $this->message->setSubject($sSubject);
        }
        $this->message->setFrom(array($this->from_addr => $this->from_name));
        return $this;
    }

    /**
     * Get Message-Object
     *
     * @return Swift_Message
     */
    public function getMessage() {
        return $this->message();
    }

    /**
     * Add Attachement by the fly without Ressource-URI from File-System
     * @param string $data
     * @param string $name
     * @param string $mimeType
     * @return $this
     */
    public function addAttachmentByData($data, $name, $mimeType = null) {
        $this->attachments[$name] = new Swift_Attachment($data, $name, $mimeType);
        return $this;
    }

    /**
     * Create Attachment by using Filesystem
     *
     * @param string $file
     * @param string $name
     * @param null|string $mimeType
     * @return $this
     */
    public function addAttachmentByFile($file, $name, $mimeType = null) {
        $this->attachments[$name] =  Swift_Attachment::fromPath($file, $name, $mimeType);
        return $this;
    }

    /**
     * Add One Header
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Add multiple Header by assoc-array
     *
     * @param array $aKeyValuePairs
     * @return $this
     */
    public function addHeaders(array $aKeyValuePairs) {
        foreach($aKeyValuePairs as $key => $value) {
            $this->addHeader($key, $value);
        }
        return $this;
    }

    /**
     * Not Yet Tested
     *
     * @todo Testing
     * @param $to
     * @param $subject
     * @param $body
     * @param $aHeader
     * @param array $aAttachments
     * @param bool $show_status
     * @return bool
     * @throws Exception
     */
    public function goSwift($to, $subject, $body, $aHeader, $aAttachments = array(), $show_status = false)
    {

        if (is_array($to)) $arrayTo = $to;
        elseif(is_scalar($to)) $arrayTo[0] = array("email" => $to, "anrede" => "");
        else return false;

        $mail_error = false;
        $mail_error_text = '';
        $aHeader = array_merge($aHeader, $this->constHeader);

        $this->createTransporter();

        $this->createDefaultMailer();

        // Or to use the Echo Logger
        $this->addLogger(new Swift_Plugins_Loggers_ArrayLogger());

        foreach($arrayTo as $ti => $_to) {

            $_body = '';

            if ($_to["anrede"]) {
                $_body .= "\r\n".$_to["anrede"]."\r\n";
            }
            $_body .= "\r\n".$body;

            $this
                ->createMessage($subject)
                ->getMessage()
                ->setTo(array( $_to['email'] ))
                ->setBody( $_body );

            // Add Attachment by structured Array with Attachmentinfos
            foreach($aAttachments as $_aItem) {

                if (!is_array($_aItem) || empty($_aItem['quelle']) || empty($_aItem['file'])) {
                    throw new Exception('Invalid Data-Structure of Attachment. Should be array with keys quelle, file!');
                }

                $mimeType = (!empty($_aItem['fmime'])) ? $_aItem['fmime'] : null;

                switch($_aItem['quelle']) {
                    case 'data':
                        $this->addAttachmentByData($_aItem['file'], $_aItem['fname'], $mimeType);
                        break;

                    case 'local':
                    case 'file':
                        $this->addAttachmentByFile($_aItem['file'], $_aItem['fname'], $mimeType);
                        break;

                    default:
                        throw new Exception( 'Invalid type of attachment. Should be data or local (file)!');
                }

            }

            $this
                ->addHeaders($aHeader)
                ->addHeader('X-QUEUE-Date', date("Y-m-d H:i:s"));

            try {
                // echo '#' . __LINE__ . ' start sending mail' . PHP_EOL;
                $result = $this->send();
            } catch(Exception $e) {
                echo '#' . __LINE__ . ' Mail-Error ' . $e->getMessage() . '' . PHP_EOL;
            }
        }

        if (count($this->logger)) {
            file_put_contents($this->logfile, print_r($this->logger[0]->dump(), 1), FILE_APPEND);
        }
    }

}


$IsStandaloneTest = true;
// TESTVERSAND: Wenn IsStandaloneTest = true, WIRD DAS SCRIPT STAND-ALONE AUGERUFEN
if ($IsStandaloneTest && basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
    // START: TESTVERSAND
    $show_status = true;
    $arrayTo[] = ["email" => "frank.barthold@googlemail.com", "anrede" => "Herr Barthold"];
//   $arrayTo[] = ["email" => "o.kowalski@mertens.ag", "anrede" => "Herr Kowalski"];
    $subject = "TEST-Mail to {to.email} Move-Management startet Portal mertens.ag";
    $textBody = "Sehr geehrte Interessentin, sehr geehrter Interessent,
 
jetzt geht?s los! Wir ziehen um!

Machen Sie mit! Wir freuen uns auf Ihren Besuch unter www.mertens.ag
 
Ihr Move-Management-Team
";
    $htmlBody = "{to.anrede},<br>
<br> 
<span style='font-weight:bold;'>jetzt geht?s los! Wir ziehen um!</span><br> 
<br> 
Machen Sie mit! Wir freuen uns auf Ihren Besuch unter www.mertens.ag<br> 
<br> 
<div style='font-style: italic'>Ihr Move-Management-Team</div>
";

    if (0) {
        $aAttachments = [
            [
                'quelle' => 'file',
                'file' => dirname(__DIR__) . '/hilfetexte/terminwunsch.php',
            ],
            [
                'quelle' => 'data',
                'file' => dirname(__DIR__) . '/hilfetexte/terminwunsch.php',
                'fname' => 'TestAnhang.html',
                'fmime' => 'text/html',
            ]
        ];
    } else {
        $aAttachments = [];
    }
    $MoveSmtp = SmtpMailer::getNewInstance();
    $numRecipients = $MoveSmtp->sendMultiMail($arrayTo, $subject, $htmlBody, $textBody, $aAttachments, $aHeader);

    if ($numRecipients) {
        echo "E-Mails wurden an $numRecipients Empfänger verschickt";
    } else {
        echo "Beim Versenden der E-Mails sind Fehler aufgetreten!";
    }

    echo "<pre><a href=\"../log/".basename($MoveSmtp->logfile).'">'.$MoveSmtp->logfile."</a></pre>\n<br>\n";
    echo "<pre>less ".$MoveSmtp->logfile."</pre>\n<br>\n";
	echo '<pre>' . PHP_EOL;
	echo htmlentities(file_get_contents($MoveSmtp->logfile));
    // ENDE: TESTVERSAND
}

