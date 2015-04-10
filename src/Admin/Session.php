<?php namespace GoBrave\Fogg\Admin;

class Session
{
  const KEY = 'fogg-session';

  const TYPE_NOTICE = 'notice';
  const TYPE_ALERT  = 'alert';

  private $session;

  public function __construct() {
    if(isset($_SESSION[self::KEY])) {
      $this->session = $_SESSION[self::KEY];
    } else {
      $this->session = $this->reset();
    }
  }

  public function message($type, $message) {
    $this->session[$type][] = $message;
    $this->update();
  }

  public function notice($message) {
    return $this->message(self::TYPE_NOTICE, $message);
  } 

  public function alert($message) {
    return $this->message(self::TYPE_ALERT, $message);
  }


  public function get($type) {
    return $this->session[$type];
  }


  public function flush($type = null) {
    if($type) {
      $this->session[$type] = [];
    } else {
      $this->session = $this->reset();
    }

    $this->update();
  }

  public function hasMessages() {
    return (bool) (
      count($this->session[self::TYPE_NOTICE])
      +
      count($this->session[self::TYPE_ALERT])
    );
  }


  private function reset() {
    return [
      self::TYPE_NOTICE => [],
      self::TYPE_ALERT  => []
    ];
  }

  private function update() {
    $_SESSION[self::KEY] = $this->session;
  }
}
