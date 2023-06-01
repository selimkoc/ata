<?php

namespace Tay;

class Router
{
  public $routes;
  public $posts;
  public $ajaxs;
  public $apis;

  public function initiate()
  {
    // Custom URLS
    $this->add_template_include();
    $this->add_rewrite_rules();
    $this->add_query_vars();

    // Wordpress Admin Posts  
    $this->add_post_routes();

    // Wordpress Ajax Posts  
    $this->add_ajax_routes();

    // Wordpress Add Custom Rest Apis
    $this->add_api_routes();
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

          $r->class = TAY_PLUGIN_NAMESPACE . "\\" . $r->class;

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

  protected function create_rule($route, $parameters)
  {

    $rule = '(' . $route;
    for ($i = 0; $i < $parameters; $i++) $rule .= '/[^/]*';
    $rule .= ')/?$';
    return $rule;
  }

  protected function add_post_routes()
  {

    foreach ($this->posts as $r) :

      add_action(Config::TAY_WP_FORM_POST_ACTION_PREFIX . $r->route, function () use ($r) {

        $r->class = TAY_PLUGIN_NAMESPACE . "\\" . $r->class;

        // Catch exception inside construct method of class
        try {

          if (!isset($_POST['action']) || !isset($_POST['security-verify'])) throw new \Exception(NO_PERMISSION);

          if (!wp_verify_nonce($_POST['security-verify'], $r->route)) throw new \Exception(NO_PERMISSION);

          $controller = new $r->class();
        } catch (\Exception $e) {

          // TODO : load exception template from theme
          echo $e->getMessage();
        }

        // Catch exception inside method of class
        try {

          call_user_func_array([$controller, $r->method], array());
        } catch (\Exception $e) {

          // TODO : load exception template from theme
          echo $e->getMessage();
        }
      });

    endforeach;
  }

  protected function add_ajax_routes()
  {

    foreach ($this->ajaxs as $r) :

      if (isset($r->guest) && $r->guest === true)
        $prefix =  Config::WP_AJAX_GUEST_ACTION_PREFIX;
      else $prefix = Config::WP_AJAX_ACTION_PREFIX;

      add_action($prefix . $r->route, function () use ($r) {
        $r->class = TAY_PLUGIN_NAMESPACE . "\\" . $r->class;

        // Catch exception inside construct method of class
        try {
          $controller = new $r->class();
        } catch (\Exception $e) {

          // TODO : May return Json object which has ID(as error code) and text
          echo $e->getMessage();
        }

        // Catch exception inside method of class
        try {

          call_user_func_array([$controller, $r->method], array());

          wp_die();
        } catch (\Exception $e) {

          // TODO : May return Json object which has ID(as error code) and text
          echo $e->getMessage();
        }
      });

    endforeach;
  }

  protected function add_api_routes()
  {

    add_action('rest_api_init', function () {

      foreach ($this->apis as $r) :

        $r->class = TAY_PLUGIN_NAMESPACE . "\\" . $r->class;
        register_rest_route($r->api_version, $r->route, array(
          'methods' => $r->api_method,
          'callback' => array(new $r->class(), $r->method),
          'permission_callback' => '__return_true',
        ));

      endforeach;
    });
  }
}
