    <?php
                  $option = array();
$option['title'] = 'Products';
    $option['col'] = array(
        0 => array('key_tab' => 'STT', 'title_tab' => 'STT', 'option_tab' => 'sort'),
        1 => array('key_tab' => 'title', 'title_tab' => 'Giá trị', 'option_tab' => ''),
        2 => array('key_tab' => 'sort', 'title_tab' => 'Giá trị đối chiếu', 'option_tab' => 'sort'),
        4 => array('key_tab' => 'description', 'title_tab' => 'Mô tả', 'option_tab' => 'sort'),
        5 => array('key_tab' => 'description', 'title_tab' => 'Ngày tạo', 'option_tab' => 'sort'),
);
echo $this->grid->create($ComparePosition, null, $option);
?>
	<?php  foreach ($ComparePosition as $key =>$promotion): ?>
<tr>
    <td><?php echo $key+1; ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['value1']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['value2']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['description']); ?>&nbsp;</td>
    <td><?php echo h($promotion['ComparePosition']['created_datetime']); ?>&nbsp;</td>
</tr>
<?php endforeach; ?>
                  <?php echo $this->grid->end_table($ComparePosition,null,$option);
       ?>


