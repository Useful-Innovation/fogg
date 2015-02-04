<?php namespace GoBrave\Fogg\Admin;

use GoBrave\Fogg\System\Controller as System;
use GoBrave\Fogg\System\Response;

class Controller extends System
{
  protected $session;

  public function __construct(Session $session) {
    $this->session = $session;
  }

  protected function response($vars = [], $type = Response::HTML) {
    return new Response($vars, $type);
  }

  protected function redirect($name, $id = null) {
    $path = explode('.', $name);

    $query = '?page=' . $path[0] . '&fogg-' . $path[1] . '=1';
    if($path[1] == 'edit') {
      $query .= '&fogg-id=' . $id;
    }
    header('Location: ' . $query);
    exit;
  }
}
