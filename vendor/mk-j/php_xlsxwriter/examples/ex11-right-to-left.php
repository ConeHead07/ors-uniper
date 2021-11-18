<?php
set_include_path( get_include_path().PATH_SEPARATOR."..");
include_once("xlsxwriter.class.php");

$header = array(
  'c1-text'=>'string',//text
  'c2-text'=>'@',//text
);
$rows = array(
  array('abcdefg','hijklmnop'),
);
$writer = new XLSXWriter();
$writer->setRightToLeft(true);

$writer->writeSheetHeader('Sheet1', $header);
foreach($rows as $row) {
    $writer->writeSheetRow('Sheet1', $row);
}
//$writer->writeToFile('xlsx-right-to-left.xlsx');

header('x-Memory-Usage: ' . floor((memory_get_peak_usage())/1024/1024) . 'MB');
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename="' . basename(__FILE__). '.xlsx"');
$writer->writeToStdOut();
