<?php
set_include_path( get_include_path().PATH_SEPARATOR."..");
include_once("xlsxwriter.class.php");

$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', array('c1'=>'integer','c2'=>'integer','c3'=>'integer','c4'=>'integer') );//optional
for($i=0; $i<250000; $i++)
{
    $writer->writeSheetRow('Sheet1', array(rand()%10000,rand()%10000,rand()%10000,rand()%10000) );
}
//$writer->writeToFile('xlsx-numbers-250k.xlsx');
header('x-Memory-Usage: ' . floor((memory_get_peak_usage())/1024/1024) . 'MB');
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename="' . basename(__FILE__). '.xlsx"');
$writer->writeToStdOut();

