<?php namespace GoBrave\Fogg\Helpers;

trait ActAsList
{
  public static function all($columns = array()) {
    $order_by = ['position ASC', 'id ASC'];
    if(isset(static::$order_by)) {
      $order_by = static::$order_by;
    }
    $query = new static();
    foreach($order_by as $order) {
      $order = explode(' ', $order);
      $query = $query->orderBy($order[0], $order[1]);
    }
    return $query->get();
  }
}
