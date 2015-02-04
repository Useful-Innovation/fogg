<?php namespace GoBrave\Fogg\System;

class Util
{
  public static function setBlogToBase() {
    \switch_to_blog(1);
  }

  public static function restoreBlogToCurrent() {
    \restore_current_blog();
  }
}
