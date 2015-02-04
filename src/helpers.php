<?php

function namedRoute($name, $params = []) {
  global $fogg;

  $router = $fogg->router();
  return $router->namedRoute($name, $params);
}


class Fogg
{
  public static function checked($value_1, $value_2 = false) {
    return self::checkedSelectedHelper($value_1, $value_2, 'checked');
  }

  public static function selected($value_1, $value_2 = false) {
    return self::checkedSelectedHelper($value_1, $value_2, 'selected');
  }

  private static function checkedSelectedHelper($value_1, $value_2, $type ) {
    if($value_2 === false) {
      return $value_1 ? $type . '="' . $type . '"' : '';
    }
    return $value_1 == $value_2 ? $type . '="' . $type . '"' : '';
  }
}
