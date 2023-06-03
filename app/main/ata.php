<?php

namespace ATA;

class Ata extends Core
{

  public function __construct()
  {
    $this->hook('init', 'loadLanguage');
  }

  public static function loadLanguage()
  {
    load_plugin_textdomain('ata', false, '/ata/languages');
  }
}
