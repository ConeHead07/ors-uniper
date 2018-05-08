<?php 
$js_numeric_code_lines = <<<HereJsDocCodeLines
   1. $(function()  
   2. {  
   3.   var hideDelay = 500;    
   4.   var currentID;  
   5.   var hideTimer = null;  
   6.   
   7.   // One instance that's reused to show info for the current person  
   8.   var container = $('<div id="personPopupContainer">'  
   9.       + '<table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="personPopupPopup">'  
  10.       + '<tr>'  
  11.       + '   <td class="corner topLeft"></td>'  
  12.       + '   <td class="top"></td>'  
  13.       + '   <td class="corner topRight"></td>'  
  14.       + '</tr>'  
  15.       + '<tr>'  
  16.       + '   <td class="left">&nbsp;</td>'  
  17.       + '   <td><div id="personPopupContent"></div></td>'  
  18.       + '   <td class="right">&nbsp;</td>'  
  19.       + '</tr>'  
  20.       + '<tr>'  
  21.       + '   <td class="corner bottomLeft">&nbsp;</td>'  
  22.       + '   <td class="bottom">&nbsp;</td>'  
  23.       + '   <td class="corner bottomRight"></td>'  
  24.       + '</tr>'  
  25.       + '</table>'  
  26.       + '</div>');  
  27.   
  28.   $('body').append(container);  
  29.   
  30.   $('.personPopupTrigger').live('mouseover', function()  
  31.   {  
  32.       // format of 'rel' tag: pageid,personguid  
  33.       var settings = $(this).attr('rel').split(',');  
  34.       var pageID = settings[0];  
  35.       currentID = settings[1];  
  36.   
  37.       // If no guid in url rel tag, don't popup blank  
  38.       if (currentID == '')  
  39.           return;  
  40.   
  41.       if (hideTimer)  
  42.           clearTimeout(hideTimer);  
  43.   
  44.       var pos = $(this).offset();  
  45.       var width = $(this).width();  
  46.       container.css({  
  47.           left: (pos.left + width) + 'px',  
  48.           top: pos.top - 5 + 'px'  
  49.       });  
  50.   
  51.       $('#personPopupContent').html('&nbsp;');  
  52.   
  53.       $.ajax({  
  54.           type: 'GET',  
  55.           url: 'personajax.aspx',  
  56.           data: 'page=' + pageID + '&guid=' + currentID,  
  57.           success: function(data)  
  58.           {  
  59.               // Verify that we're pointed to a page that returned the expected results.  
  60.               if (data.indexOf('personPopupResult') < 0)  
  61.               {  
  62.                   $('#personPopupContent').html('<span >Page ' + pageID + ' did not return a valid result for person ' + currentID + '.  
  63. Please have your administrator check the error log.</span>');  
  64.               }  
  65.   
  66.               // Verify requested person is this person since we could have multiple ajax  
  67.               // requests out if the server is taking a while.  
  68.               if (data.indexOf(currentID) > 0)  
  69.               {                    
  70.                   var text = $(data).find('.personPopupResult').html();  
  71.                   $('#personPopupContent').html(text);  
  72.               }  
  73.           }  
  74.       });  
  75.   
  76.       container.css('display', 'block');  
  77.   });  
  78.   
  79.   $('.personPopupTrigger').live('mouseout', function()  
  80.   {  
  81.       if (hideTimer)  
  82.           clearTimeout(hideTimer);  
  83.       hideTimer = setTimeout(function()  
  84.       {  
  85.           container.css('display', 'none');  
  86.       }, hideDelay);  
  87.   });  
  88.   
  89.   // Allow mouse over of details without hiding details  
  90.   $('#personPopupContainer').mouseover(function()  
  91.   {  
  92.       if (hideTimer)  
  93.           clearTimeout(hideTimer);  
  94.   });  
  95.   
  96.   // Hide after mouseout  
  97.   $('#personPopupContainer').mouseout(function()  
  98.   {  
  99.       if (hideTimer)  
 100.           clearTimeout(hideTimer);  
 101.       hideTimer = setTimeout(function()  
 102.       {  
 103.           container.css('display', 'none');  
 104.       }, hideDelay);  
 105.   });  
 106. });
HereJsDocCodeLines;

$aJsCodeLines = explode("\n", $js_numeric_code_lines);
for($i = 0; $i < count($aJsCodeLines); $i++) {
	$aJsCodeLines[$i] = substr($aJsCodeLines[$i], 6);
}
echo "<pre>";
echo fb_htmlEntities(implode("\n", $aJsCodeLines));
echo "</pre>\n";
 ?>
