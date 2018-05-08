<?php 

session_start();

//$area = (!empty($_POST["area"]) ? $_POST["area"] : (!empty($_GET["area"]) ? $_GET["area"] : ""));
$aLanguages = array("cn","de","en","es");
if (!empty($_GET["ln"]) && in_array($_GET["ln"], $aLanguages)) $ln = $_GET["ln"];
elseif (!empty($_SESSION["accept_lang"])) $ln = $_SESSION["accept_lang"];
elseif (!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) $ln = strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2));
if ($ln && !in_array($ln, $aLanguages)) $ln = "";

$ausgabe_mainfile = dirname(__FILE__)."/../../html/index_baustelle.html";

if (empty($ln)) {

		switch($ip2c) {
			case "CHN":
			$ausgabe_mainfile = dirname(__FILE__)."/../../html/index_baustelle_cn.html";
			$ln = "cn";
			break;
			
			case "ESP": // SPAIN
			case "PRT": // PORTUGAL
			case "ARG": // ARGENTINA
			case "BRA": // BRAZIL
			break;
			
			// Deutsch
			case "DEU":
			case "AUT":
			case "CHE":
			$ln = "de";
			break;
			
			default:
			$ln = "en";
			break;
		}
}
$_SESSION["accept_lang"] = $ln;
$text = implode("", file(dirname(__FILE__)."/lang/".$ln.".html"));
$ausgabe = implode("", file($ausgabe_mainfile));
echo str_replace("{content}", $text, $ausgabe);

include_once(dirname(__FILE__)."/../../lib/weblog_extended.php");
exit;
?>