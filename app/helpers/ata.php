<?php

namespace Ata;

function str($text, $domain = Config::TEXT_DOMAIN)
{
    return _e($text, $domain);
}
