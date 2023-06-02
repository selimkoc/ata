<?php

namespace Ata;

class Ajax_Router extends Router
{
  private $prefix;


  public function main()
  {
    // Wordpress Ajax Posts  
    $this->add_ajax_routes();
  }


  protected function add_ajax_routes()
  {

    foreach ($this->ajaxs as $route) :

      $this->route = $route;

      $this->set_prefix();

      $this->add_action();

    endforeach;
  }

  protected function add_action()
  {

    add_action($this->prefix . $this->route->route, function () use ($this) {

      $this->create_controller();

      $this->call_method();
    });
  }

  protected function create_controller()
  {

    $this->route->class = ATA_PLUGIN_NAMESPACE . "\\" . $this->route->class;

    // Catch exception inside construct method of class
    try {
      $this->controller = new $this->route->class();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function call_method()
  {

    // Catch exception inside method of class
    try {

      call_user_func_array([$this->controller, $this->route->method], array());

      wp_die();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }


  protected function set_prefix()
  {

    if (isset($this->route->guest) && $this->route->guest === true)
      $this->prefix =  Config::WP_AJAX_GUEST_ACTION_PREFIX;
    else
      $this->prefix = Config::WP_AJAX_ACTION_PREFIX;
  }
}
