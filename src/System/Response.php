<?php namespace GoBrave\Fogg\System;

class Response
{
  const HTML = 'html';
  const JSON = 'json';

  private $vars;
  private $type;

  public function __construct(array $vars = [], $type = self::HTML) {
    $this->vars = $vars;
    $this->type = $type;
  }

  public function respondWith($type) {
    $this->type = $type;
  }

  public function asJSON() {
    $this->respondWith(self::JSON);
  }

  public function asHTML() {
    $this->respondWith(self::HTML);
  }

  public function vars() {
    return $this->vars;
  }

  public function type() {
    return $this->type;
  }
}
