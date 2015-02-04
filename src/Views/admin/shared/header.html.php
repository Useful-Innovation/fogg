<div class="wrap fogg-wrap">
  <h2>
    <?php if($route->isEdit()) : ?>
      Redigerar <?= $model->t('title'); ?>
    <?php elseif($route->isCreate()) : ?>
      <?= $route->resource()->prefix; ?> <?= $route->resource()->singular; ?>
    <?php else : ?>
      <?= $route->resource()->plural; ?>
    <?php endif; ?>
    <a href="?page=<?= $_GET['page']; ?>&<?= $route::TYPE_CREATE; ?>=1" class="add-new-h2">
      Lägg till <?= strtolower($route->resource()->singular); ?>
    </a>
  </h2>
  <?php if($session->hasMessages()) : ?>
    <?= $this->render('admin/shared/messages'); ?>
  <?php endif; ?>
