<?php namespace GoBrave\Fogg\Front;

use GoBrave\Fogg\System\Controller as System;
use GoBrave\Fogg\System\Response;
use GoBrave\Fogg\System\Renderer;

class Controller extends System
{
  protected $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  protected function response($vars = [], $type = Response::HTML) {
    return new Response($vars, $type);
  }
}
