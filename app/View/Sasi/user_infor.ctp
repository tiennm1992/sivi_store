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
<table style="width: 100%; " >
    <tr>
        <td style="width: 20%">Tên</td>
        <td><?php echo $user_infor['username']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Họ và tên</td>
        <td><?php echo $user_infor['name']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Giới tính</td>
        <td><?php if($user_infor['gender']){echo 'Nam';}else{ echo 'Nữ';}?></td>
    </tr>
    <tr>
        <td style="width: 20%">Số điện thoại</td>
        <td><?php echo $user_infor['phone']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Ngày sinh</td>
        <td><?php echo $user_infor['birthday']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Địa chỉ</td>
        <td><?php echo $user_infor['address']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Email</td>
        <td><?php echo $user_infor['email']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Chứng minh thư</td>
        <td><?php echo $user_infor['cmtnd']?></td>
    </tr>
    <tr>
        <td style="width: 20%">Ngân hàng</td>
        <td><?php echo $user_infor['bank_atm']?></td>
    </tr>
    <tr>
        <td style="width: 20%">ID Sale</td>
        <td><?php echo $user_infor['code']?></td>
    </tr>
</table>


<div class="actions">
   <a href="/users/edit_sasi" type="button" class="btn btn-primary" >Chỉnh sửa thông tin cá nhân</a>
</div> 
