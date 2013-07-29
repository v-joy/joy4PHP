<table width="100%" id="frame_table">
    <tr>
        <td>字段</td>
        <td>值</td>
    </tr>
<?php 
//D($this->values,true);
foreach($this->columns as $column) {?>
    <tr>
        <td><?php echo $column['Field'];?></td>
        <td><?php echo $this->values[$column['Field']];?> </td>
    </tr>
<?php }?>
</table>