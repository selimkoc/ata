<?php

namespace ATA;

class Post_Router extends Router
{

  protected function __construct($posts)
  {
    parent::__construct();
    $this->posts = $posts;
    $this->add_post_routes();
  }
  protected function add_post_routes()
  {

    foreach ($this->posts as &$this->route)  $this->add_action();
  }

  protected function add_action()
  {
    add_action(Config::$wp_form_post_action_prefix . $this->route->route, $this->actions());
  }

  protected function actions()
  {
    $this->set_controller_name();

    $this->initiate_controller();

    $this->call_method();
  }

  protected function initiate_controller()
  {

    try {

      $this->check_wp_nonce();

      $this->controller = new $this->controller();
    } catch (\Exception $e) {

      $this->handle_exception($e);
    }
  }

  protected function call_method()
  {

    try {
      call_user_func_array([$this->controller, $this->route->method], array());
    } catch (\Exception $e) {
      $this->handle_exception($e);
    }
  }

  protected function check_wp_nonce()
  {

    if (!isset($_POST['action']) || !isset($_POST['security-verify'])) throw new \Exception(str(Text::NO_PERMISSION), 1);

    if (!wp_verify_nonce($_POST['security-verify'], $this->route->route)) throw new \Exception(str(Text::NO_PERMISSION), 1);
  }
}
