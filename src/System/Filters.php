<?php namespace GoBrave\Fogg\System;

trait Filters
{
  public function runBeforeFilters($method) {
    $this->runFilter('before');
    $this->runFilter('before' . $method);
  }

  public function runAfterFilters($method) {
    $this->runFilter('after' . $method);
    $this->runFilter('after');
  }

  protected function runFilter($name) {
    if(method_exists($this, $name)) {
      return call_user_func([$this, $name]);
    }
  }
}
