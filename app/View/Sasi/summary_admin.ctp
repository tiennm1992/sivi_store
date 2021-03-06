<style>
    table {
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    table, td, th {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
        font-size: 16px;
    }
    .border_line{
        border: 1px solid black
    }
    .text_1{

        color: #3c8dbc;
        font-weight:bold;
        font-size: 18px;
        padding: 10px;

    }
    .text_2{

        color: #ff3333;
        font-weight:bold;
        font-size: 20px;

    }
    .text_3{

        color: #000000;
        font-weight:bold;
        font-size: 20px;


    }

</style>
<div class="row">
    <form method="get" >
        <div class="col-xs-12">
            <div class="form-group col-xs-12 col-sm-3">
                <label style= 'padding-top: 5px; font-size: 18px;' for="date">Thống kê theo tháng : </label>
            </div>
            <div class="form-group col-xs-12 col-sm-5">
                <div >
                    <input   style= 'font-size: 18px;' type="month" class="form-control valid" name="date" id="date" value="<?php echo $date1; ?>">
                    </span>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-2">
                <label for="date"></label>
                <input style= 'font-size: 18px;' type="submit" class="btn btn-primary" name="search" value="search">
            </div>
        </div>
    </form>
</div>
<table style="width: 100%; " >
    <tr>
        <td colspan="5">
            <p  class="text_2">Chức danh hiện tại</p>
            <p><?php echo $current_position ?></p>
        </td>
        <td colspan="5" >
            <?php echo $user_name ?>
        </td>
        <td colspan="5" > 
            <p class="text_2">Chức danh cao nhất</p>
            <p><?php echo $best_position ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="text_3"> Activity</td>
        <td colspan="3"   style="width: 20%">
            <p class="text_1" >SPB</p>    
            <?php echo $number_buy ?>
        </td>
        <td colspan="3"   style="width: 20%">
            <p class="text_1" >DT</p>    
            <p><?php echo number_format($sasi['revenue'], 0, '', '.'); ?></p>
        </td>
        <td colspan="3"   style="width: 20%">
            <p class="text_1" >Đr</p>    
            <p><?php echo $sasi['point_dr'] ?></p>
        </td>
        <td colspan="3"   style="width: 20%">
            <p class="text_1" >Đ</p>    
            <p><?php echo $sasi['point_dr'] + $sasi['point_dc'] ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="3" rowspan="2" class="text_3">Team-Group</td>
        <td colspan="6" class="text_1" style="width: 40%">
            <p class="text_3">T-SASI</p>
            <P><?php echo $sasi_list['count'] ?></P>
        </td>
        <td colspan="6" class="text_1" style="width: 40%">
            <p class="text_3">N-SASI</p>
            <p><?php echo $sasi_list['newbie'] ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p>Sasim</p>    
            <p><?php echo $sasi_list['sasim'] ?></p>    
        </td>
        <td colspan="2">
            <p>Sasima</p>    
            <p><?php echo $sasi_list['sasima'] ?></p>    
        </td>
        <td colspan="2">
            <p>Sasime</p>    
            <p><?php echo $sasi_list['sasime'] ?></p>    
        </td>
        <td colspan="2">
            <p>Sasimi</p>    
            <p><?php echo $sasi_list['sasimi'] ?></p>    
        </td>
        <td colspan="2">
            <p>Sasimo</p>    
            <p><?php echo $sasi_list['sasimu'] ?></p>    
        </td>
        <td colspan="2">
            <p>Sasimu</p>    
            <p>0</p>
        </td>

    </tr>
    <tr>
        <td colspan="3" class="text_3">Income</td>
        <td colspan="2" >
            <p class="text_1">LN</p>
            <p><?php echo number_format($sasi['profit'], 0, '', '.'); ?></p>
        </td>
        <td colspan="2" >
            <p class="text_1">CC</p>
            <p><?php echo number_format($sasi['profit_cc'], 0, '', '.'); ?></p>
        </td>
        <td colspan="2" >
            <p class="text_1">TN</p>
            <p><?php echo number_format($sasi['profit'] + $sasi['profit_cc'], 0, '', '.'); ?></p>
        </td>
        <td colspan="3" >
            <p class="text_1">T-KH</p>
            <p><?php echo $number_customer ?></p>
        </td>
        <td colspan="3" >
            <p class="text_1">N-KH</p>
            <p><?php echo $new_num_customer ?></p>
        </td>
    </tr>


</table>
