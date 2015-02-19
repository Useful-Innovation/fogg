<?php namespace App\Fogg\System;

use GoBrave\Fogg\Front\Controller as Fogg;
use GoBrave\Fogg\System\Renderer;

class FrontController extends Fogg
{
  protected $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }
}
