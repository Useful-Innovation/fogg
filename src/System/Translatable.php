<?php namespace GoBrave\Fogg\System;

trait Translatable
{
  use Translator;

  public function translate($field, $language = null) {
    if($language === null) {
      $language = self::languageClass($this);
      $language = $language::main();
    }

    $translation = $this->translations()->where('language_id', '=', $language->id)->first();
    if($translation) {
      return $translation->{$field};
    }
    return false;
  }

  public function translations() {
    return $this->hasMany($this->translationClassName(true));
  }

  public function updateAttributes($params) {
    $translations = $params['translations'];
    unset($params['translations']);

    $this->fill($params);
    $this->save();

    $class = $this->translationClassName(true);
    foreach($translations as $translation) {
      $class::createOrUpdateByParent($this, $translation);
    }
  }


  //
  //
  //    Helpers
  //
  //
  private function translationClassName($with_namespace = false) {
    $class = get_class($this);
    if($with_namespace == false) {
      $class = explode('\\', $class);
      $class = end($class);
    }

    return $class . 'Translation';
  }
}
