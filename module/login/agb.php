<?php 

require_once(dirname(__FILE__)."/../../lib/conf.php");
require_once(dirname(__FILE__)."/../../lib/conn.php");

$SQL = "SELECT * FROM ctv_cms_texte \n";
$SQL.= " WHERE seitenbereich LIKE \"agb\" AND notation LIKE \"agb\" AND webfreigabe = \"Ja\"\n";
$SQL.= " ORDER BY ordnungszahl ASC\n";
$SQL.= " LIMIT 1";

$r = MyDB::query($SQL, $connid);
if ($r) {
   $n = MyDB::num_rows($r);
   if ($n) {
      $e = MyDB::fetch_array($r);
      // echo "<h1>".$e["listentitel"]."</h1>\n";
      // echo $e["listentext"]."\n";
   }/**/
   MyDB::free_result($r);
} else echo MyDB::error()."<br>".$SQL."<br>\n";

if (empty($e)) die("Interner Fehler!");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>AGB: Best Of Germany</title>
	<script>self.focus()</script>
	<style>
	.contentBox {
		padding:10px;
		background:#fff;
	}
	body {
		margin:0px;
		padding:0px;
		background:#f3f3f3;
	}
	body,
	body * {
		font-family:Arial;
		font-size:12px;
		color:navy;
	}
	h1 {
		font-size:18px;
		color:#000000;
		margin:10px 0px 15px 0px;
	}
	</style>
</head>

<body><div class="contentBox">
<h1><?php echo $e["listentitel"]; ?></h1>

<?php echo $e["listentext"]; ?>
</div>
<script>
document.write("<div style=\"height:20px;background:#eaeaea;border-top:1px solid gray;border-bottom:1px solid gray;text-align:center;text-align:center;\"><button onclick=self.close()>OK</button></div>");
self.focus()
</script>
</body>
</html>
