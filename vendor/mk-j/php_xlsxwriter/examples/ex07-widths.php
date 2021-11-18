<?php
set_include_path( get_include_path().PATH_SEPARATOR."..");
include_once("xlsxwriter.class.php");


$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', $rowdata = array(300,234,456,789), $col_options = ['widths'=>[10,20,30,40]] );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $row_options = ['height'=>20] );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $row_options = ['height'=>30] );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $row_options = ['height'=>40] );
//$writer->writeToFile('xlsx-widths.xlsx');
header('x-Memory-Usage: ' . floor((memory_get_peak_usage())/1024/1024) . 'MB');
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename="' . basename(__FILE__). '.xlsx"');
$writer->writeToStdOut();

