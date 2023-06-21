<?php

namespace ATA;

class Router extends Core
{
  public $urls;
  public $posts;
  public $ajaxs;
  public $apis;

  protected $permissions;

  protected $controller;

  protected $route;

  protected $on = null;
  protected $filter = null;


  protected $priority;

  protected $arguments;

  protected function __construct()
  {
    parent::__construct();
    // Default permissions 
    $this->permissions[] = ['rule' => 'guests', 'callback' => 'is_user_logged_in'];
    $this->permissions[] = ['rule' => 'members', 'callback' => '!is_user_logged_in'];
  }

  public function init()
  {
    if (!empty($this->urls) && count($this->urls)) new UrlRouter($this->urls);
    if (!empty($this->posts) && count($this->posts)) new PostRouter($this->posts);
    if (!empty($this->ajaxs) && count($this->ajaxs)) new AjaxRouter($this->ajaxs);
    if (!empty($this->apis) && count($this->apis)) new ApiRouter($this->apis);
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

  public function wp_hook($on)
  {
    $this->on = $on;
    $this->arguments = 1;
    $this->priority = 10;
    return $this;
  }
  public function wp_filter($filter)
  {
    $this->filter = $filter;
    $this->arguments = 1;
    $this->priority = 10;
    return $this;
  }
  public function priority($priority)
  {
    $this->priority = $priority;
    return $this;
  }

  public function arguments($priority)
  {
    $this->priority = $priority;
    return $this;
  }

  public function static($static)
  {
    if ($this->on !== null) :
      parent::on($this->on, $static, $this->priority, $this->arguments);
      $this->on = null;
    elseif ($this->filter !== null) :
      parent::filter($this->filter, $static, $this->priority, $this->arguments);
      $this->filter = null;
    endif;
  }

  public function dynamic($dynamic)
  {
    if ($this->on !== null) :
      parent::on(
        $this->on,
        function ($args) use ($dynamic) {
          $dynamic = explode('::', $dynamic);
          $this->controller = Config::$plugin_namespace . "\\" . $dynamic[0];
          $this->controller =  new $this->controller();
          $this->controller->{$dynamic[1]}($args);
        },
        $this->priority,
        $this->arguments
      );
      $this->on = null;
    elseif ($this->filter !== null) :
      parent::filter(
        $this->filter,
        function ($args) use ($dynamic) {
          $dynamic = explode('::', $dynamic);
          $this->controller = Config::$plugin_namespace . "\\" . $dynamic[0];
          $this->controller =  new $this->controller();
          $this->controller->{$dynamic[1]}($args);
        },
        $this->priority,
        $this->arguments
      );

      $this->filter = null;
    endif;
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
    $this->controller = Config::$plugin_namespace . "\\" . $this->route->class;
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
