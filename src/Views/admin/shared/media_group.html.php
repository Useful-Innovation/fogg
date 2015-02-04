<div class="group">
  <?= $this->render('admin/shared/media_field', [
      'type'  => $type,
      'name'  => $name,
      'value' => $value
    ]);
  ?>
  <p class="remove-group-container">
    <button class="duplicatable-button remove-group button button-secondary">Ta bort <?= $type == 'image' ? 'bild' : 'fil'; ?></button>
  </p>
</div>
