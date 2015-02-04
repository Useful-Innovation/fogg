<?php namespace GoBrave\Fogg\System;

use GoBrave\Util\Renderer as Util;
use GoBrave\Fogg\System\Translatable;

class Renderer extends Util
{
  protected $vars;

  public function __construct($templates_paths = null, $type = null) {
    parent::__construct($templates_paths, $type);
    $this->vars = [];
  }

  public function setDefaultVars(array $vars) {
    $this->vars = array_merge($this->vars, $vars);
    return $this;
  }

  public function render($template, $vars = [], $type = null) {
    $vars = array_merge($vars, $this->vars);
    return parent::render($template, $vars, $type);
  }




  //
  //
  //    Rendering functions
  //
  //
  protected function printRelation($model, $relation) {
    if(!method_exists($model, $relation)) {
      return $model->{$relation};
    }

    $result = $model->{$relation}();
    if($result instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
      $temp = [];
      foreach($result->get() as $item) {
        $temp[] = $item;
      }
      $result = $temp;
    } else {
      $result = [$result];
    }

    $tmp = [];

    foreach($result as $part) {
      if(!$part) {
        continue;
      }

      $traits = $this->usesTraits($part);
      if(in_array('GoBrave\Fogg\System\Translatable', $traits)) {
        $tmp[] = $part->t('title');
      } else {
        $tmp[] = method_exists($part, 'title') ? $part->title() : $tmp[] = $part->title;
      }
    }

    return implode(', ', $tmp);
  }

  private function usesTraits($class, $autoload = true) {
    $traits = [];

    // Get traits of all parent classes
    do {
      $traits = array_merge(class_uses($class, $autoload), $traits);
    } while ($class = get_parent_class($class));

    // Get traits of all parent traits
    $traitsToSearch = $traits;
    while (!empty($traitsToSearch)) {
      $newTraits      = class_uses(array_pop($traitsToSearch), $autoload);
      $traits         = array_merge($newTraits, $traits);
      $traitsToSearch = array_merge($newTraits, $traitsToSearch);
    };

    foreach ($traits as $trait => $same) {
      $traits = array_merge(class_uses($trait, $autoload), $traits);
    }

    return array_unique($traits);
  }
}
