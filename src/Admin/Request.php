<?php namespace GoBrave\Fogg\Admin;

class Request
{
  private $get;
  private $post;
  private $files;

  public function __construct($get, $post, $files) {
    $this->get  = $this->convertStringsToDataTypes($get);
    $this->post = $this->convertStringsToDataTypes($post);
    $this->files = $files;
  }

  private function convertStringsToDataTypes($arr) {
    foreach($arr as $key => $var) {
      if($var === '') {
        $arr[$key] = null;
      } else if(is_numeric($var)) {
        // if the numeric value has a '.' in it, it's a float
        $arr[$key] = strpos($var, '.') === false ? intval($var) : floatval($var);
      } else if(is_array($var)) {
        $arr[$key] = $this->convertStringsToDataTypes($var);
      }
    }
    return $arr;
  }

  public function get($key = false) {
    if($key === false) {
      return $this->get;
    }
    return $this->getVar('get', $key);
  }

  public function post($key = false) {
    if($key === false) {
      return $this->post;
    }
    return $this->getVar('post', $key);
  }

  public function files($key = false) {
    if($key === false) {
      return $this->files;
    }
    return $this->getVar('files', $key);
  }

  private function getVar($var, $key) {
    if(!isset($this->{$var}[$key])) {
      return false;
    }
    return $this->{$var}[$key];
  }
}
