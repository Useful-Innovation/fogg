<ul class="translatable-tabs">
  <?php foreach($languages as $lang) : ?>
    <input type="hidden" name="<?= $name; ?>[translations][<?= $lang->id; ?>][language_id]" value="<?= $lang->id; ?>" />
    <li class="lang-<?= $lang->code; ?>">
      <a href="#translatable-<?= $lang->code; ?>" title="<?= $lang->title; ?>">
        <?= $lang->title; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
