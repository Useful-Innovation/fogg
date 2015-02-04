<?= '<?='; ?> $this->render('admin/shared/header'); ?>
<?= '<?='; ?> $this->render('admin/shared/table', [
  'models' => $<?= $plural; ?>,
  'cols'   => []
]); ?>
<?= '<?='; ?> $this->render('admin/shared/footer'); ?>
