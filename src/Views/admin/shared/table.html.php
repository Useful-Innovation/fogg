<table class="wp-list-table widefat fixed pages" cellspacing="0">
  <thead>
    <?= $this->render('/admin/shared/table_header', ['cols' => @$cols]); ?>
  </thead>
  <tfoot>
    <?= $this->render('/admin/shared/table_header', ['cols' => @$cols]); ?>
  </tfoot>
  <tbody id="the-list">
    <?php if(count($models) > 0) : ?>
      <?php foreach($models as $key => $model) : ?>
        <?php if(isset($extra_value)): ?>
          <?php $parent = $model->{$extra_value}(); ?>
        <?php endif; ?>
        <tr class="<?= ($key % 2 === 0 ? 'alternate' : ''); ?>" valign="top">
          <td></td>
          <td class="post-title page-title column-title">
            <strong><a class="row-title" href="?page=<?= $_GET['page']; ?>&<?= $route::TYPE_EDIT; ?>=1&fogg-id=<?= $model->id; ?><?= isset($extra_key) && isset($parent) ? '&'.$extra_key.'='.$parent->id : ''; ?>" title="Redigera <?= $model->t('title'); ?>"><?= ($model->t('title') ?: '(titel saknas)'); ?></a></strong>
            <div class="row-actions">
              <span class="edit">
                <a href="?page=<?= $_GET['page']; ?>&<?= $route::TYPE_EDIT; ?>=1&fogg-id=<?= $model->id; ?>" title="Redigera denna post">Redigera</a>
              </span>
              <span class="delete button-delete">
                | <a class="submitdelete <?= $route::TYPE_DELETE; ?>" title="Flytta denna post till papperskorgen" href="?page=<?= $_GET['page']; ?>&<?= $route::TYPE_DELETE; ?>=1&fogg-id=<?= $model->id; ?>">Ta bort</a>
              </span>
            </div>
          </td>
          <?php if(isset($cols) AND count($cols)) : ?>
            <?php foreach($cols as $inner_key => $value) : ?>
               <td>
                <?= $this->printRelation($model, $inner_key); ?>
              </td>
            <?php endforeach; ?>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr><td colspan="2">Hittade inga <?= strtolower($route->resource()->plural); ?></td></tr>
    <?php endif; ?>
  </tbody>
</table>
