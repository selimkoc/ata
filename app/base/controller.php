<?php

namespace ATA;

class Controller extends Core
{
  public $view;
  protected $ata;

  protected function __construct()
  {
    parent::__construct();
    global $ata;
    $this->ata = &$ata;
    $this->ata = (object) [];
  }

  /**
   * Check if current user has permissions
   * 
   */
  protected function has_permissions()
  {
    $parameters = func_get_args();
    $has_permission = false;
    $throw_exception = true;

    // check for last parameter for throwing exception or not 
    if (count($parameters) > 1) {
      if (is_bool($parameters[count($parameters) - 1])) {
        // remove last parameter from array and set $throw_exception
        $throw_exception = array_pop($parameters);
      }
    }

    // check for permissions
    foreach ($parameters as $permission)
      if (current_user_can($permission))
        $has_permission = true;

    // throw exception if no permission and $throw_exception is true
    if ($has_permission == false && $throw_exception == true)
      throw new \Exception(str(Texts::NO_PERMISSION), 1);

    return $has_permission;
  }

  public function handle_exception($exception)
  {
    $this->ata->message = $exception->getMessage();
    $this->view = 'exception';
  }

  protected function load_user_info($user_id = null)
  {
    if ($user_id === null) :
      $this->ata->user = wp_get_current_user();
    else :
      $this->ata->current_user =  get_user_by('id', $user_id);
    endif;

    $this->ata->user_id = $this->ata->user->ID;
  }
}
