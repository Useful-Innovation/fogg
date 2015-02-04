<?php namespace GoBrave\Fogg\System;

trait Translator
{
  protected static function modelNamespace($model) {
    $class = get_class($model);
    $class = explode('\\', $class);
    array_pop($class);
    return implode('\\', $class);
  }

  protected static function languageClass($model) {
    return self::modelNamespace($model) . '\\Language';
  }
}
