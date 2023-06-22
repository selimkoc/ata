<?php

namespace ATA;

class UrlRouter extends Router
{
  protected function __construct($routes)
  {
    parent::__construct();
    $this->urls = $routes;
    $this->add_template_include();
    $this->add_rewrite_rules();
    $this->add_query_vars();
  }

  protected function add_template_include()
  {
    add_action('template_include', function ($template) {

      foreach ($this->urls as &$this->route) :

        if (isset($this->route->permission))
          if ($this->check_permissions()) continue;

        if (get_query_var($this->route->route) != false || get_query_var($this->route->route) != '') {

          return $this->run_controller();
        }
      endforeach;

      return $template;
    });
  }

  protected function run_controller()
  {

    $this->set_controller_name();

    $this->initiate_controller();

    $this->call_method();

    return Config::$view_path . $this->controller->view . '.php';
  }


  protected function add_rewrite_rules()
  {

    add_action('init',  function () {

      foreach ($this->urls as &$this->route) :

        if (isset($this->route->permission))
          if ($this->check_permissions()) continue;

        add_rewrite_rule($this->create_rule(), 'index.php?' . $this->route->route . '=$matches[1]', 'top');

      endforeach;
    });
  }

  protected function add_query_vars()
  {


    add_filter('query_vars', function ($query_vars) {

      foreach ($this->urls as &$this->route) :

        if (isset($this->route->permission))
          if ($this->check_permissions()) continue;

        $query_vars[] =  $this->route->route;

      endforeach;

      return $query_vars;
    });
  }


  protected function initiate_controller()
  {

    try {
      $this->controller = new $this->controller();

      $this->initiate_model();

      $this->set_view();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function initiate_model()
  {

    if (isset($this->route->model)) :
      $this->controller->model =  '\\' . Config::$plugin_namespace . '\\' . $this->route->model;
      $this->controller->model = new $this->controller->model();
    endif;
  }

  protected function set_view()
  {

    if (isset($this->route->view)) $this->controller->view = $this->route->view;
  }
  protected function call_method()
  {

    // Catch exception inside method of class
    try {

      $parameters = explode("/", get_query_var($this->route->route));

      unset($parameters[0]);

      call_user_func_array([$this->controller, $this->route->method], $parameters);
    } catch (\Exception $e) {

      $this->controller->handle_exception($e);
    }
  }

  protected function handle_exception($e)
  {

    global $ata;

    $ata =  (object)[];

    $ata->exception = $e;

    return Config::$view_path . 'exception.php';
  }
}
