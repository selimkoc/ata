<?php

namespace Ata;

function str($text, $domain = Config::TEXT_DOMAIN)
{
    return _e($text, $domain);
}

function inc_folder($folder)
{

    if ($handle = opendir($folder)) {
        while (false !== ($entry = readdir($handle))) {
            if (strpos($entry, '.php') !== false) require_once $folder . $entry;
        }
        closedir($handle);
    }
}
