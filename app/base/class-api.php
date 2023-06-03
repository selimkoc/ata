<?php

namespace ATA;

class Api
{
  protected function __construct()
  {
  }

  protected function response($data, $code = 200)
  {

    return new \WP_REST_Response($data, $code);
  }
}
