<?php 

function json_escape($str) {
	return strtr($str, array("\n"=>"\\n","\r"=>"\\r","\""=>"\\\""));
}

?>