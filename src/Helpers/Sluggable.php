<?php namespace GoBrave\Fogg\Helpers;

trait Sluggable
{
  public function save(array $options = array()) {
    $slug = $this->toSlug($this->title);
    $slug = $this->generateUniqueSlug($slug);
    $this->slug = $slug;
    parent::save($options);
  }

  private function toSlug($text) {
    $text = str_replace('å', 'a', $text);
    $text = str_replace('ä', 'a', $text);
    $text = str_replace('ö', 'o', $text);
    $text = str_replace('Å', 'A', $text);
    $text = str_replace('Ä', 'A', $text);
    $text = str_replace('Ö', 'O', $text);

    // To lower case have to be below ÅÄÖ. ÅÄÖ will not become åäö after strtolower()
    $text = strtolower($text);
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }

  private function generateUniqueSlug($slug) {
    $model = $this->findSluggableModelBySlug($slug);

    if(!$model) {
      return $slug;
    }

    $i = 1;
    while($this->findSluggableModelBySlug($slug . '-' . $i)) {

      if($i > 10) {
        break;
      }

      $i++;
    }

    return $slug . '-' . $i;
  }

  private function findSluggableModelBySlug($slug) {
    $tmp = self::where('slug', $slug)->where('language_id', $this->language_id);

    if($this->exists) {
      $tmp = $tmp->where('id', '!=', $this->id);
    }

    return $tmp->first();
  }
}
