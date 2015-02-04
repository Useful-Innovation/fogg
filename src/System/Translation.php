<?php namespace GoBrave\Fogg\System;

trait Translation
{
  public static function createOrUpdateByParent($parent, $translation) {
    $parent_class = get_class($parent);
    $relation_key = self::$caser->camelToSnake(self::getClass($parent)) . '_id';

    $object = self::where($relation_key, '=', $parent->id)->where('language_id', '=', $translation['language_id'])->first();
    if(!$object) {
      $object = new static();
    }

    $object->{$relation_key} = $parent->id;
    foreach($translation as $key => $value) {
      $object->{$key} = $value;
    }

    $object->save();
    return $object;
  }


  //
  //
  //    Helpers
  //
  //
  private static function getClass($model, $with_namespace = false) {
    $class = get_class($model);
    if($with_namespace == false) {
      $class = explode('\\', $class);
      $class = end($class);
    }

    return $class;
  }
}
