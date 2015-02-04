<?= '<?='; ?> $this->render('admin/shared/header', ['model' => $<?= $singular; ?>]); ?>
<?= '<?='; ?> $this->render('admin/shared/form_header', ['model' => $<?= $singular; ?>]); ?>
<?= '<?='; ?> $this->render('admin/<?= $plural; ?>/_form', ['<?= $singular; ?>' => $<?= $singular; ?>]); ?>
<?= '<?='; ?> $this->render('admin/shared/form_footer'); ?>
<?= '<?='; ?> $this->render('admin/shared/footer'); ?>
