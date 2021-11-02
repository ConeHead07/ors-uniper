<?php
function smarty_function_ors_include_static_content($params, &$smarty) {

    if(!isset($params['file'])) {
        $smarty->trigger_error("gravatar: no 'file' attribute passed");
        return;
    }

    $dir = $params['dir'] ?? 'textfiles';
    $file = $params['file'];

    $appdir = dirname(dirname(__DIR__));

    $filePath = $appdir . '/' . basename($dir) . '/'. $file;
    if (file_exists($filePath)) {
        return file_get_contents($filePath);
    } else {
        return 'File ' . $file . ' not found in ' . $dir . '!';
    }
}
