<table class="wp-list-table widefat fixed pages" cellspacing="0">
  <thead>
    <?php $this->render('/admin/shared/table_header', array('cols' => @$cols)); ?>
  </thead>
  <tfoot>
    <?php $this->render('/admin/shared/table_header', array('cols' => @$cols)); ?>
  </tfoot>
  <tbody id="the-list">
    <?php if(count($models) > 0) : ?>
      <?php foreach($models as $key => $model) : ?>
        <tr class="<?php echo ($key % 2 === 0 ? 'alternate' : ''); ?>" valign="top">
          <td></td>
          <td class="post-title page-title column-title">
            <strong><a class="row-title" href="?page=<?php echo $_GET['page']; ?>&<?= \App\Api\AdminRouter::KEY_EDIT; ?>=<?php echo $model->id; ?>" title="Redigera <?php echo $model->t('title'); ?>"><?php echo ($model->t('title') ?: '(titel saknas)'); ?></a></strong>
            <div class="row-actions">
              <span class="edit">
                <a href="?page=<?php echo $_GET['page']; ?>&<?= \App\Api\AdminRouter::KEY_EDIT; ?>=<?php echo $model->id; ?>" title="Redigera denna post">Redigera</a>
              </span>
              <span class="delete">
                | <a class="submitdelete <?= \App\Api\AdminRouter::KEY_DELETE; ?>" title="Flytta denna post till papperskorgen" href="?page=<?php echo $_GET['page']; ?>&<?= \App\Api\AdminRouter::KEY_DELETE; ?>=<?php echo $model->id; ?>">Ta bort</a> | 
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
      <tr><td colspan="2">Hittade inga <?php echo strtolower($route->resource()->plural); ?></td></tr>
    <?php endif; ?>
  </tbody>
</table>
