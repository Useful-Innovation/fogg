<?php namespace GoBrave\Fogg\System;

trait Image
{
  protected function imageIdToUrl($image_id, $size = 'thumbnail') {
    if(!$image_id) {
      return '';
    }

    Util::setBlogToBase();
    $src = wp_get_attachment_image_src($image_id, $size);
    if(!$src) {
      Util::restoreBlogToCurrent();
      return false;
    }

    $url = ($src ? $src[0] : false);

    Util::restoreBlogToCurrent();
    return $url;
  }
}
