<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 17.11.2021
 * Time: 18:33
 */

echo '<ol>';
foreach (glob(__DIR__ . "/ex[0-9]*.php") as $file) {
    $filename = basename($file);
    echo "<li><a href='$filename' target='$filename' data-size='" . filesize($file) . "'>$filename</a></li>\n";
}
echo '</ol>';
