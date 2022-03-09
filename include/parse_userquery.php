<?php 

function userquery_split($userquery, $fld, $autotrunc = "Both") {
  $aUQueryParts = userquery_parse($userquery);
  
  $sql = userquery_parts2sql($aUQueryParts, $fld, $autotrunc);
  $aTerms = userquery_get_terms($aUQueryParts);
  return array($sql, $aTerms);
}

function userquery_get_terms($aUQueryParts) {
  
  $aTerms = array();
  for($i = 0; $i < count($aUQueryParts); $i++) {
    if ($aUQueryParts[$i][0] == "term") {
      $aTerms[] = $aUQueryParts[$i][1];
    }
  }
  return $aTerms;
}

// @param string autotrunc = Both, End, Front, None
function userquery_parts2sql($aUQueryParts, $fld, $autotrunc = "Both") {
  $sql = "";
  //echo "#".__LINE__." userquery_parts2sql() aUQueryParts:".print_r($aUQueryParts,1)."<br>\n";
  
  $fT = ($autotrunc == "Both" || $autotrunc == "Front") ? "%" : ""; // FrontTrunkierung
  $eT = ($autotrunc == "Both" || $autotrunc == "End") ? "%" : ""; // EndTrunkierung
  //echo "#".__LINE__." ".basename(__FILE__)." $fld autotrunc=$autotrunc; fT:$fT; eT:$eT <br>\n";
  for($i = 0; $i < count($aUQueryParts); $i++) {
    if ($aUQueryParts[$i][0] == "term") {
      $term = $aUQueryParts[$i][1];
      if ($i && $aUQueryParts[$i-1][0] == "op") {
        $op = $aUQueryParts[$i-1][1];
        switch($op) {
          case "OR":
          case "AND":
          case "OR NOT":
          case "AND NOT":
          $sql.= " $op $fld LIKE \"$fT".str_replace("*","%",MyDB::escape_string($term))."$eT\"";
          //echo "#".__LINE__." ".basename(__FILE__)." sql:$sql <br>\n";
          break;
          
          case "NOT":
          $opBefore = ($i-1 && $aUQueryParts[$i-2][0] == "op") ? $aUQueryParts[$i-2][1] : "AND";
          if (!trim($sql)) $opBefore = "";
          $sql.= " $opBefore $fld $op LIKE \"$fT".MyDB::escape_string($term)."$eT\"";
          break;
          
          default:
          $opBefore = ($i-1 && $aUQueryParts[$i-2][0] == "op") ? $aUQueryParts[$i-2][1] : "AND";
          if (!trim($sql)) $opBefore = "";
          $sql.= " $opBefore $fld $op \"".MyDB::escape_string($term)."\"";
          //echo "#".__LINE__." ".basename(__FILE__)." sql:$sql <br>\n";
        }
      } else {
        $sql.= "$fld LIKE \"$fT".str_replace("*","%",MyDB::escape_string($term))."$eT\"";
        //echo "#".__LINE__." ".basename(__FILE__)." sql:$sql <br>\n";
      }
    }
  }
  //echo "#".__LINE__." ".basename(__FILE__)." sql:$sql <br>\n";
  return $sql;
}

function userquery_parse($userquery) {
  //echo "#".__LINE__." userquery:".fb_htmlEntities($userquery)."<br>\n";
  $rest = trim($userquery);
  $aUQueryParts = array();
  $escChr = "\\";
  $aPhraseChr = array("\"", "'");
  $aSeparatorChr = array(
    ", "     => "OR",
    ","     => "OR",
    ";"     => "OR",
    " or "  =>"OR",
    " and " =>"AND",
    "and " =>"AND",
    "+" =>"AND",
    " not " =>"AND NOT",
    "not " =>"NOT",
    " and not " =>"AND NOT",
    " or not " =>"OR NOT",
    ", not " =>"OR NOT",
    "-" =>"AND NOT",
    " != " =>"!=",
    " !=" =>"!=",
    "!= " =>"!=",
    "!=" =>"!=",
    "=" =>"=",
    " "     => "AND",
    " < "     =>"<",
    " <"     =>"<",
    "<"     =>"<",
    " <= "    =>"<=",
    " <="    =>"<=",
    "<="    =>"<=",
    " > "     =>">",
    " >"     =>">",
    ">"     =>">",
    " >= "    =>">=",
    " >="    =>">=",
    ">="    =>">=");
  
  $loops = 0;
  $max_loops = 500;
  while($rest) {
    if ($loops++ > $max_loops) {
      echo "Too much loops: $loops!";
      break;
    }
    $foundEndOfPh = "";
    $foundSep = false;
    
    // Test, ob nächstes Element eine Phrase ist
    if (in_array($rest[0], $aPhraseChr)) {
      $ph = $rest[0];
      $offset = 1;
      while(!$foundEndOfPh) {
        $foundEndOfPh = strpos($rest, $ph, $offset);
        if (!is_int($foundEndOfPh)) {
          $foundEndOfPh = false;
          break;
        }
        if (substr(str_replace('\\', 'aa', substr($rest, 0, $foundEndOfPh)), -1)=="\\") {
          $offset = $foundEndOfPh;
          $foundEndOfPh = false;
        }
      }
    }
    
    if ($foundEndOfPh) {
      $aUQueryParts[] = array("term",substr($rest, $ph+1, $foundEndOfPh-$ph-1));
      $rest = substr($rest, $foundEndOfPh+1);
    } else {
      while(substr($rest, 0, 2) == "  ") $rest = substr($rest,1);
      
      // Test, ob n�chstes Element ein Operator-Zeichen ist
      //echo "#".__LINE__." chck4Op in rest:$rest|<br>\n";
      foreach($aSeparatorChr as $r_chr => $r_op) {
        if ($r_chr == substr($rest, 0, strlen($r_chr))) {
          $aUQueryParts[] = array("op",$r_op,$r_chr);
          $rest = substr($rest, strlen($r_chr));
          $foundSep = true;
          //echo "#".__LINE__." IsOp:$_chr|".substr($rest, 0, strlen($r_chr))."|<br>\n";
          break;
        } else {
          //echo "#".__LINE__." IsNotOp:$r_chr|".substr($rest, 0, strlen($r_chr))."|<br>\n";
        }
      }
      
      if (!$foundSep) {
        // Ermittel Ende von einfachem Suchstring
        $foundEndOfString = false;
        for($i = 0; $i < strlen($rest); $i++) {
          
          foreach($aSeparatorChr as $r_chr => $r_op) {
            if ($r_chr == strtolower(substr($rest, $i, strlen($r_chr)))) {
              $aUQueryParts[] = array("term",substr($rest,0, strpos($rest, $r_chr)));
              $rest = substr($rest, strpos($rest, $r_chr));
              $foundEndOfString = true;
              break 2;
            }
          }
          
        }
        if (!$foundEndOfString) {
          $aUQueryParts[] = array("term",$rest);
          $rest = "";
        }
      }
    }
    
  }
  return $aUQueryParts;
}

if (basename(__FILE__) == basename($_SERVER["PHP_SELF"])) {
  echo "<html><head><title>Untitled</title>
  <style>
  strong {
    font-weight:bold;
    font-size:13px;
    color:#fff;
    background:#000;
  }
  </style></head><body>\n";
  $fld = "`main`.`text`";
  $userquery = "not 'Klaus Devender', not \"Frank Barthold\" or or Vicky, Matze >500 < 600 != 550";
  $userquery = "not 000002, 000003, 000005";
  //$userquery = "not 5, 000002 , 000003 , 000005";
  echo "userquery: ".fb_htmlEntities($userquery)."<br>\n";
  
  $aUQueryParts = userquery_parse($userquery, "Both");
  echo "<pre>".print_r($aUQueryParts,1)."</pre>\n";
  
  $sql = userquery_parts2sql($aUQueryParts, $fld);
  echo "<pre>".strtr(fb_htmlEntities(print_r($sql,1)), array("&lt;"=>"<","&gt;"=>">"))."</pre>\n";
  
  echo "<pre>userquery_split():\n".print_r(userquery_split($userquery, $fld), 1)."</pre>";
  echo "</body>\n</html>";
}

?>
