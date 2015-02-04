<div class="splitter splitter-two">

  <div class="postbox">

  </div>

</div>

<?php if($translatable) : ?>

<div class="translatable-container">

  <?= '<?='; ?> $this->render('admin/shared/translatable_tabs', ['name' => '<?= $singular; ?>']); ?>

  <div class="translatable-inside">

    <div class="postbox">
      <h3>Titel</h3>
      <div class="inside">

        <?= '<?php'; ?> foreach($languages as $lang) : ?>
          <div class="field translatable translatable-<?= '<?='; ?> $lang->code; ?>">
            <div class="text-field">
              <input type="hidden" name="<?= $singular; ?>[translations][<?= '<?='; ?> $lang->id; ?>][language_id]" value="<?= '<?='; ?> $lang->id; ?>" />
              <input type="text" name="<?= $singular; ?>[translations][<?= '<?='; ?> $lang->id; ?>][title]" value="<?= '<?='; ?> $<?= $singular; ?>->t('title', $lang); ?>" />
            </div>
          </div>
        <?= '<?php'; ?> endforeach; ?>

      </div>
    </div>

  </div>

</div>

<?php endif; ?>
