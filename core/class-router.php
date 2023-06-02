<?php

namespace Ata;

class Router
{

  public $routes;
  public $posts;
  public $ajaxs;
  public $apis;

  protected $controller;

  protected $route;


  protected function __construct()
  {
  }

  protected function create_rule($route, $parameters)
  {
    $rule = '(' . $route;
    for ($i = 0; $i < $parameters; $i++) $rule .= '/[^/]*';
    $rule .= ')/?$';
    return $rule;
  }

  protected function handle_exception($e)
  {
    // TODO : May return Json object which has ID(as error code) and text
    echo $e->getMessage();
  }


  protected function set_controller_name()
  {
    $this->controller = ATA_PLUGIN_NAMESPACE . "\\" . $this->route->class;
  }
}
