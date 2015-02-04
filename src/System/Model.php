<?php namespace GoBrave\Fogg\System;

use Illuminate\Database\Eloquent\Model as Eloquent;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\SingPlur;

class Model extends Eloquent
{
  protected static $caser;
  protected static $singplur;

  public function __construct(array $attributes = []) {
    parent::__construct($attributes);

    if(!isset(self::$caser)) {
      self::$caser= new CaseConverter();
    }
    if(!isset(self::$singplur)) {
      self::$singplur= new SingPlur();
    }
  }
}
