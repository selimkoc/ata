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
  protected function hook($action, $method, $priority = 10, $accepted_args = 1)
  {
    add_action($action, [$this, $method], $priority, $accepted_args);
  }

  /**
   * Replace wordpress function with your method
   *
   * @param [String] $filter , e.g.  "wp_nav_menu_args" 
   * @param [String] $method , e.g. "myMethod", "my_method"
   * @return void
   */

  protected function filter($filter, $method, $priority = 10, $accepted_args = 1)
  {
    add_filter($filter, [$this, $method], $priority, $accepted_args);
  }
}
