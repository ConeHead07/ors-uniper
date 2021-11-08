<?php
/**
 * Created by PhpStorm.
 * User: f.barthold
 * Date: 04.11.2021
 * Time: 18:25
 */
use \module\userprofile\UserProfile;

$up = new UserProfile();

$body_content = <<<EOT
<div class="divModuleBasic padding6px width5Col heightAuto colorContentMain">
    <h1><span class="spanTitle">Benutzerprofil</span></h1>

    <div id="Auswertung" class="divInlay">
EOT;


$body_content.= $up->getEditableProfile($user);

$body_content.= <<<EOT
    </div>
</div>
EOT;

// die($body_content);
