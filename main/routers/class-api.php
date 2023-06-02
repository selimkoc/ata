<?php

namespace Ata;

class Api_Router extends Router
{
  public function main()
  {
    // Wordpress Add Custom Rest Apis
    $this->add_api_routes();
  }

  protected function add_api_routes()
  {

    add_action('rest_api_init', function () {

      foreach ($this->apis as &$this->route) :

        $this->set_controller_name();

        $this->register_api_endpoint();

      endforeach;
    });
  }


  protected function register_api_endpoint()
  {

    register_rest_route($this->route->api_version, $this->route->route, array(
      'methods' => $this->route->api_method,
      'callback' => array(new $this->controller(), $this->route->method),
      'permission_callback' => '__return_true',
    ));
  }
}
