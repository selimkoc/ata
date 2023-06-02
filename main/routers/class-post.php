<?php

namespace Ata;

class Post_Router extends Router
{


  public function main()
  {
    // Wordpress Admin Posts  
    $this->add_post_routes();
  }
  protected function add_post_routes()
  {

    foreach ($this->posts as &$this->route)  $this->add_action();
  }

  protected function add_action()
  {

    add_action(Config::WP_FORM_POST_ACTION_PREFIX . $this->route->route, function () use ($this) {

      $this->set_controller_name();

      $this->create_controller();

      $this->call_method();
    });
  }

  protected function create_controller()
  {

    // Catch exception inside construct method of class
    try {

      $this->check_wp_nonce();

      $this->controller = new $this->controller();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function call_method()
  {

    // Catch exception inside method of class
    try {

      call_user_func_array([$this->controller, $this->route->method], array());
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function check_wp_nonce()
  {

    if (!isset($_POST['action']) || !isset($_POST['security-verify'])) throw new \Exception(_e("Permission denied", "ata"), 1);

    if (!wp_verify_nonce($_POST['security-verify'], $this->route->route)) throw new \Exception(_e("Permission denied", "ata"), 1);
  }
}
