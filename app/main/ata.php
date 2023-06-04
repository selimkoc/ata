<?php

namespace ATA;

class Ata extends Core
{

  public function __construct()
  {
    $this->on('init', 'load_language_translations');
  }

  public static function load_language_translations()
  {
    load_plugin_textdomain(Config::TEXT_DOMAIN, false, '/ata/languages');
  }
}
