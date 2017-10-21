    <?php
                  $option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'STT', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'title', 'title_tab' => 'Giá trị', 'option_tab' => ''),
        2 => array('key_tab' => 'sort', 'title_tab' => 'Cách quy đổi', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'description', 'title_tab' => 'Mô tả', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'description', 'title_tab' => 'Ngày tạo', 'option_tab' => 'sort'),
);
echo $this->grid->create($ExchangePositions, null, $option);
?>
	<?php  foreach ($ExchangePositions as $key =>$promotion): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($promotion['ExchangePositions']['value1']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ExchangePositions']['value2']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ExchangePositions']['description']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ExchangePositions']['created_datetime']); ?>&nbsp;</td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($ExchangePositions,null,$option);
       ?>

