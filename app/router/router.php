<?php

namespace ATA;

class Router
{
  public $urls;
  public $posts;
  public $ajaxs;
  public $apis;

  protected $permissions;

  protected $controller;

  protected $route;

  protected function __construct()
  {
    // Default permissions 
    $this->permissions[] = ['rule' => 'guests', 'callback' => 'is_user_logged_in'];
    $this->permissions[] = ['rule' => 'members', 'callback' => '!is_user_logged_in'];
  }

  public function main()
  {
    if (!empty($this->urls) && count($this->urls)) new Url_Router($this->urls);
    if (!empty($this->posts) && count($this->posts)) new Post_Router($this->posts);
    if (!empty($this->ajaxs) && count($this->ajaxs)) new Ajax_Router($this->ajaxs);
    if (!empty($this->apis) && count($this->apis)) new Api_Router($this->apis);
  }

  public function route($route)
  {
    $this->route = (object) ['route' => $route, 'parameters' => 0];
    return $this;
  }
  public function param($parameters)
  {
    $this->route->parameters = $parameters;
    return $this;
  }

  public function call($callback)
  {
    $parts = explode('::', $callback);
    $this->controller($parts[0]);
    $this->method($parts[1]);
  }

  public function controller($controller)
  {
    $this->route->controller = $controller;
  }
  public function method($method)
  {
    $this->route->method = $method;
    $this->urls[] = $this->route;
  }

  protected function create_rule()
  {
    // Add prefix to the route
    $rule = '(' . $this->route->route;

    // Add number of parameters to the route
    for ($i = 0; $i < $this->route->parameters; $i++)
      $rule .= '/[^/]*';

    // Add suffix to the route        
    $rule .= ')/?$';

    return $rule;
  }

  protected function handle_exception($e)
  {
    echo $e->getMessage();
  }

  protected function set_controller_name()
  {
    $this->controller = Config::$my_plugin_namespace . "\\" . $this->route->class;
  }

  protected function check_permissions()
  {

    foreach ($this->permissions as $permission) :

      if ($this->route ===  $permission['rule'])
        return $this->run_permission_callback($permission['callback']);

    endforeach;

    return false;
  }

  protected function run_permission_callback($callback)
  {

    if (strpos($callback, '::') !== false) {
      $this->run_permission_callback_controller($callback);
    } else {
      if (strpos($callback, '!') === false)
        return $callback();
      else
        return !$callback();
    }
  }

  protected function run_permission_callback_controller($callback)
  {

    $callback = explode('::', $callback);

    if (strpos($callback[0], '!') === false) {
      $my_class = new $callback[0]();
      return $my_class->{$callback[1]}();
    } else {
      $callback[0] = substr($callback[0], 1);
      $my_class = new $callback[0]();
      return !$my_class->{$callback[1]}();
    }
  }
}
