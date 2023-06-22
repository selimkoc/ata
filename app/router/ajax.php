<?php

namespace ATA;

class AjaxRouter extends Router
{
  private $prefix;

  protected function __construct($ajaxs)
  {
    parent::__construct();
    $this->ajaxs = $ajaxs;
    $this->add_ajax_routes();
  }


  protected function add_ajax_routes()
  {

    foreach ($this->ajaxs as &$this->route) :

      $this->set_prefix();

      $this->add_action();

    endforeach;
  }

  protected function add_action()
  {
    add_action($this->prefix . $this->route->route, [$this, 'actions']);
  }
  protected function actions()
  {
    $this->initiate_controller();

    $this->call_method();
  }

  protected function initiate_controller()
  {

    $this->route->class = Config::$plugin_namespace . "\\" . $this->route->class;

    try {
      $this->controller = new $this->route->class();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function call_method()
  {

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
      $this->prefix = Config::$wp_ajax_guest_action_prefix;
    else
      $this->prefix = Config::$wp_ajax_action_prefix;
  }
}
