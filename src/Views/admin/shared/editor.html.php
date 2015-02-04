<div class="editor-field">
  <?php
    wp_editor(
      $content,
      $id,
      [
        'media_buttons' => false,
        'textarea_name' => $name
      ]
    );
  ?>
</div>
