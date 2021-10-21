<?php 

$body_content.= "Willkommen ".$user["vorname"]." ".$user["nachname"]."!<br>\n";

switch($user["gruppe"]) {
	
	case "kunde_report":
        $redirect = "?s=Property";
        break;
	
	case "admin_standort":
	case "admin_gesamt":
        $redirect = "./?s=aantraege";
        break;

    case "umzugsteam":
        // die('#' . __LINE__ . ' ' . __FILE__ . ' : ' . $user['gruppe']);
        $redirect = "./?s=aantraege";
        break;
	
	case "user":
	default:
        header("Location: ./index.php?s=Umzug"); exit;
        break;
}
header("Location: $redirect");
exit;
