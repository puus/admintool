<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php ob_start(); ?>
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="/user/do_login" method="POST">
              <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Email" name="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-8">
                  <div class="checkbox icheck">

                    <label>
                        <input type="checkbox" name="remember">
                      &nbsp;Remember me
                    </label>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>

            <a href="/user/forgotpswd">I forgot my password</a><br>
            <!--<a href="register.html" class="text-center">Register</a>-->
<?php
$content = ob_get_clean();

$template = $this->load->view('framework/unauthenticated.php', [
    'title' => 'Login',
    'content' => $content
]);
?>
