<form method="post" class="fogg-form" id="poststuff">

  <?php if($route->isEdit() OR $route->isCreate()) : ?>
    <p>
      <button class="button button-primary" type="submit">Spara</button>
      <?php if($route->isEdit()) : ?>
        <a class="button button-delete <?= $route::TYPE_DELETE; ?>" href="?page=<?php echo $_GET['page']; ?>&<?= $route::TYPE_DELETE; ?>=1&fogg-id=<?= $model->id; ?>">Ta bort</a>
      <?php endif; ?>
    </p>
  <?php endif; ?>
