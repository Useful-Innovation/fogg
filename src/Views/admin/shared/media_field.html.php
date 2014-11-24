<div class="media-field" data-type="<?= $type; ?>">
  <figure>
    <img src="<?= $value['src']; ?>" alt="" />
  </figure>
  <input type="hidden" class="value-field" name="<?= $name; ?>" value="<?= $value['id']; ?>" />
  <button class="button button-primary fogg-choose-media">Välj</button>
  <button class="button delete fogg-remove-media">Ta bort</button>
</div>
