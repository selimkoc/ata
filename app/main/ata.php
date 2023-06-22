<?php

namespace ATA;

class Ata extends Core
{

  public function __construct()
  {
    parent::__construct();
    $this->on('init', 'load_language_translations');
    $this->on('plugins_loaded', 'load_routes');
  }

  public static function load_language_translations()
  {
    load_plugin_textdomain(Config::TEXT_DOMAIN, false, Config::$plugin_folder . '/vendor/ata/languages');
  }

  public function load_routes()
  {
    $ata_router = new Router();

    $routes_dir = Config::$plugin_path . "/app/routes/";

    foreach (files_in($routes_dir) as $file) require_once $file;

    $ata_router->init();
  }
}
