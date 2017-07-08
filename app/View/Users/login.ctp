<div class="login-box">
    <div class="login-logo">
        <b>Sivi Store </b>Admin
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Đăng nhập vào hệ thống</p>
         <?php echo $this->Session->flash("error"); ?>
        <form  id="UserAdminLoginForm" method="post" accept-charset="utf-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="data[User][username]" id="UserUsername" placeholder="Username"  value="<?php echo isset($_COOKIE['usr'])?$_COOKIE['usr']:''; ?>">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="data[User][password]" id="UserPassword" placeholder="password" value="<?php echo isset($_COOKIE['pwd'])?$_COOKIE['pwd']:''; ?>">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">    
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="data[User][remember]" checked="checked" value="1"> Ghi nhớ đăng nhập
                        </label>
                    </div> 
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng Nhập</button>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div>

