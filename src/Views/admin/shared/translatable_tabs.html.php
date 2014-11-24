<ul class="translatable-tabs">
  <?php foreach(\App\Api\Models\Language::all() as $lang) : ?>
    <li class="lang-<?= $lang->code; ?>">
      <a href="#translatable-<?= $lang->code; ?>" title="<?= $lang->title; ?>">
        <?= $lang->title; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>