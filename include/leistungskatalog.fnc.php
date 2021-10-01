<?php

/**
 * 
 * @param int $id
 * @return string
 */
function leistung_einheit2($id) {
    /* @var $db dbconn */
    global $db;
    $sql = 'SELECT leistungseinheit2 FROM mm_leistungskatalog WHERE leistung_id = :id';
    return (string)$db->query_one($sql, array('id' => $id));
}

function formatEingabeLeistungskatalogImage(
        $editCmd,
        $fN,
        $val,
        $fC,
        $arrDbdata,
        $arrInput,
        &$tplForm,
        $needle
    ) {
    $script = "<script>$(function() {
        var inputImg = $(\"input[name=eingabe\\\\[$fN\\\\]]\");
        var inputBox = $(\"#InputBox{$fN} .inputFrm\");
        var imgPreview = $(\"<img/>\").css({ maxWidth: 500, maxHeight: 200 });
        var imgHref = $(\"<a/>\").attr({target: \"_img\"});
        imgPreview.appendTo(imgHref);
        imgHref.appendTo(inputBox);
        inputImg.on(\"change\", function() {
            if (!this.value) {
                imgHref.hide();
                imgPreview.attr(\"src\", \"\");
                imgPreview.hide();
                imgPreview.on(\"error\", function() {
                    imgPreview.hide();
                });
                
                return;
            }
            var imgPath = \"images/leistungskatalog/\" + this.value;
            imgHref.show().attr({href: imgPath });
            imgPreview.show().attr(\"src\", imgPath);
        })
        .trigger(\"change\");
    });
            </script>";
    $tplForm.= $script;
}

function formatLesenLeistungskatalogImage(
    $editCmd,
    $fN,
    &$val,
    $fC,
    $rrDbdata,
    $arrInput,
    &$tplForm,
    $needle
) {
    $val = ' <a href="' . htmlentities($val) . '" target=_img>'
           . '<img src="' . $val . '" style="max-width:500px;max-height:200px;"><br>'
           . $val
           . '</a>';

}

