<?= $php; ?> namespace <?= $namespace; ?>; 

<?php foreach($uses as $use) : ?>
use <?= $use; ?>;
<?php endforeach; ?>

class <?= $class; ?> extends <?= $parent; ?> 
{
  protected $fillable = [];
}
