<div class="media-field" data-type="<?= $type; ?>">
  <?php if($type === 'image') : ?>
    <figure>
      <img src="<?= $value['src']; ?>" alt="" />
    </figure>
  <?php elseif($type === 'file') : ?>
    <p>
      <a target="_blank" href="<?= $value['src']; ?>"><?= $value['title']; ?></a>
    </p>
  <?php endif; ?>
  <input type="hidden" class="value-field" name="<?= $name; ?>" value="<?= $value['id']; ?>" />
  <button class="button button-primary fogg-choose-media">Välj</button>
  <button class="button button-delete fogg-remove-media">Ta bort</button>
</div>
