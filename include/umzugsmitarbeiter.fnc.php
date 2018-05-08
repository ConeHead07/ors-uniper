<?php 

function umzugsmitarbeiter_check_fon(&$fN, &$arrInput, &$arrConf, &$err) {
	if (trim($arrInput[$fN]) == "N" || strtolower(trim($arrInput[$fN])) == "kein") {
            return true;
        } elseif (trim($arrInput[$fN])) {
		$muster = "/^[0-9]*$/";
		$string = strtr($arrInput[$fN], array("+"=>""," "=>"",'/'=>"","("=>"",")"=>""));
		$match_result = preg_match($muster, $string);
		if (!$match_result) $err = $arrInput[$fN]." ist keine gültige Telefonnr!";
		return $match_result;
		echo "muster: $muster<br>\nstring: $string<br>\nmatch_result: ".print_r($match_result,1)."<br>\n";
	}
        return ($arrConf["Fields"][$fN]["required"]) ? false : true;
}

?>