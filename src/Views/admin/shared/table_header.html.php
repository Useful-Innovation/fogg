<tr>
  <th scope="col" class="check-column" style="">
  </th>
  <th scope="col" id="title" class="column-title" style="">
    <span>Titel</span>
  </th>
  <?php if(isset($cols) AND count($cols)) : ?>
    <?php foreach($cols as $key => $value) : ?>
       <th scope="col" id="<?php echo $key; ?>">
        <span><?php echo $value; ?></span>
      </th>
    <?php endforeach; ?>
  <?php endif; ?>
</tr>