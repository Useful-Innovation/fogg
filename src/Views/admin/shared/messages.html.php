<div class="fogg-messages">
  <?php foreach($session->get($session::TYPE_ALERT) as $message) : ?>
    <?= $this->render('admin/shared/message', ['type' => $session::TYPE_ALERT, 'message' => $message]); ?>
  <?php endforeach; ?>
  <?php foreach($session->get($session::TYPE_NOTICE) as $message) : ?>
    <?= $this->render('admin/shared/message', ['type' => $session::TYPE_NOTICE, 'message' => $message]); ?>
  <?php endforeach; ?>
</div>
