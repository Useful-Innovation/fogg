<?php namespace GoBrave\Fogg\System;

trait File
{
  protected function fileIdToUrl($file_id) {
    if(!$file_id) {
      return '';
    }

    Util::setBlogToBase();
    $url = wp_get_attachment_url($file_id);
    Util::restoreBlogToCurrent();
    if(!$url) {
      return false;
    }
    return $url;
  }

  protected function filenameFromFileId($file_id) {
    $url = $this->fileIdToUrl($file_id);
    if(!$url) {
      return false;
    }

    $url = explode('/', $url);
    return array_pop($url);
  }
}
