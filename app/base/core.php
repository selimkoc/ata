<?php

namespace ATA;

class Core
{
  protected function __construct()
  {
  }

  /**
   * Hook your method to Wordpress events
   *
   * @param [String] $action , e.g.  "init", "load"
   * @param [String] $method , e.g. "myMethod", "my_method"
   * @return void
   */
  protected function hook($action, $method)
  {
    add_action($action, [$this, $method]);
  }

  /**
   * Replace wordpress function with your method
   *
   * @param [String] $filter , e.g.  "wp_nav_menu_args" 
   * @param [String] $method , e.g. "myMethod", "my_method"
   * @return void
   */

  protected function filter($filter, $method)
  {
    add_filter($filter, [$this, $method]);
  }
}
