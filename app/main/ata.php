<?php

namespace ATA;

class Ata
{
  public $view;
  protected $tay;

  public function __construct()
  {
    $this->bind();
  }

  protected function bind()
  {
    add_action('init', [$this, 'load_language']);
  }

  public static function load_language()
  {
    load_plugin_textdomain('ata', false, '/ata/languages');
  }
}
