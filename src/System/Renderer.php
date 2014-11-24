<?php namespace GoBrave\Fogg\System;

use GoBrave\Util\Renderer as Util;

class Renderer extends Util
{
  protected $vars;

  public function setDefaultVars(array $vars) {
    $this->vars = $vars;
  }

  public function render($template, $vars = [], $type = null) {
    $vars = array_merge($vars, $this->vars);
    return parent::render($template, $vars, $type);
  }
}
