<?php namespace GoBrave\Fogg\Admin;

class Request
{
  private $get;
  private $post;

  public function __construct($get, $post) {
    $this->get  = $get;
    $this->post = $post;
  }

  public function get($key) {
    return $this->getVar('get', $key);
  }

  public function post($key) {
    return $this->getVar('post', $key);
  }

  private function getVar($var, $key) {
    if(!isset($this->{$var}[$key])) {
      return false;
    }
    return $this->{$var}[$key];
  }
}
