<?php
    require_once "config.php";
    $redirectURL = "http://localhost/FacebookLogin/fb-callback.php";
    $permissions = ['email'];
    $loginURL = $helper->getLoginURL($redirectURL, $permissions);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport"
        content="width-device-width, user-scalable=no, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
            <title>Log</title>

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            </head>

            <body>

              <div class="container" style="margin-top: 100px">
                  <div class="row justify-content-center">
                    <div class="col-md6 col-md-offset-3" align="center">
                        <form>
                              <input name="email" placeholder="Email" class="form-control"><br>
                                    <input name="password" type="password" placeholder="Password"
                                class="form-control"
                              ><br>
                                          <input type="submit" value="Log In" class="btn btn-primary">
                                                <input type="button" onclick="window.location='<?php echo $loginURL; ?>'" value="Log In With Facebook"
                                        class="btn btn-primary">
                                                </form>
                                                  </div>
                                                    </div>
                                                </div>

                                                </body>

                                                </html>
