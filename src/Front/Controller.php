<?php namespace GoBrave\Fogg\Front;

use GoBrave\Fogg\System\Controller as System;
use GoBrave\Fogg\System\Response;

class Controller extends System
{
  protected function response($vars = [], $type = Response::HTML) {
    return new Response($vars, $type);
  }
}
