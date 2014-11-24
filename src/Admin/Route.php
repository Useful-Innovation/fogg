<?php namespace GoBrave\Fogg\Admin;

use GoBrave\Fogg\Config;

class Route
{
  const TYPE_INDEX  = 'fogg-index';
  const TYPE_CREATE = 'fogg-create';
  const TYPE_EDIT   = 'fogg-edit';
  const TYPE_DELETE = 'fogg-delete';

  private $controller;
  private $method;
  private $request_method;
  private $type;
  private $resource;


  private $__method = [
    'POST' => [
      self::TYPE_CREATE => 'store',
      self::TYPE_EDIT   => 'update'
    ],
    'GET' => [
      self::TYPE_INDEX  => 'index',
      self::TYPE_CREATE => 'create',
      self::TYPE_EDIT   => 'edit',
      self::TYPE_DELETE => 'delete'
    ]
  ];

  public function __construct(Config $config, $resource) {
    $this->config   = $config;
    $this->resource = $resource;
  }

  public function prepare() {
    $this->method($this->__method[$this->request_method][$this->type]);

    $controller = $this->config->baseNamespace() . '\\Controllers\\Admin\\' . $this->controller . $this->config->controllerSuffix();
    if(!class_exists($controller)) {
      throw new \Exception('Controller \'' . $controller . '\' does not exists');
    }

    $this->controllerClass($controller);
  }



  public function isEdit() {
    return $this->method() == 'edit';
  }

  public function isCreate() {
    return $this->method() == 'create';
  }

  public function isIndex() {
    return $this->method() == 'index';
  }












  //
  //
  //    Setters
  //
  //
  public function controller($controller = null) {
    return $this->getSetVar('controller', $controller);
  }

  public function method($method = null) {
    return $this->getSetVar('method', $method);
  }

  public function requestMethod($request_method = null) {
    return $this->getSetVar('request_method', $request_method);
  }

  public function type($type = null) {
    return $this->getSetVar('type', $type);
  }

  public function resource($resource = null) {
    return $this->getSetVar('resource', $resource);
  }

  public function controllerClass($controller_class = null) {
    return $this->getSetVar('controller_class', $controller_class);
  }

  private function getSetVar($key, $var) {
    if($var === null) {
      return $this->{$key};
    }
    $this->{$key} = $var;
    return $this;
  }
}
