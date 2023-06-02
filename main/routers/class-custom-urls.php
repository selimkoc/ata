<?php

namespace Ata;

class Custom_Urls_Router extends Router
{
  public function main()
  {
    // Custom URLS
    $this->add_template_include();
    $this->add_rewrite_rules();
    $this->add_query_vars();
  }

  protected function add_template_include()
  {
    add_action('template_include', function ($template) {

      foreach ($this->routes as $r) :

        if (isset($r->permission))
          if ($r->permission === 'guests') {
            if (is_user_logged_in()) continue;
          } else if ($r->permission === 'players') {
            if (!is_user_logged_in()) continue;
          }

        if (get_query_var($r->route) != false || get_query_var($r->route) != '') {

          $r->class = ATA_PLUGIN_NAMESPACE . "\\" . $r->class;

          // Catch exception inside construct method of class
          try {
            $controller = new $r->class();
          } catch (\Exception $e) {

            global $data;

            $data =  (object)[];

            $data->message = $e->getMessage();

            return get_template_directory() . '/templates/exception.php';
          }

          $parameters = explode("/", get_query_var($r->route));

          unset($parameters[0]);

          // Catch exception inside method of class
          try {

            call_user_func_array([$controller, $r->method], $parameters);
          } catch (\Exception $e) {

            $controller->handle_exception($e);
          }

          return get_template_directory() . '/' . $controller->view . '.php';
        }
      endforeach;

      return $template;
    });
  }


  protected function add_rewrite_rules()
  {

    add_action('init',  function () {

      foreach ($this->routes as $r) :

        if (isset($r->permission))
          if ($r->permission === 'guests')
            if (is_user_logged_in()) continue;

        $rule = $this->create_rule($r->route, $r->parameters);

        add_rewrite_rule($rule, 'index.php?' . $r->route . '=$matches[1]', 'top');

      endforeach;
    });
  }

  protected function add_query_vars()
  {

    add_filter('query_vars', function ($query_vars) {
      foreach ($this->routes as $r) :

        if (isset($r->permission))
          if ($r->permission === 'guests')
            if (is_user_logged_in()) continue;

        $query_vars[] =  $r->route;
      endforeach;


      return $query_vars;
    });
  }
}
