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
    load_plugin_textdomain(Config::TEXT_DOMAIN, false, '/ata/languages');
  }

  public function load_routes()
  {
    $router = new Router();

    $routes_dir = Config::$plugin_path . "/routes/";

    inc_folder($routes_dir);

    $router->init();
  }
}
