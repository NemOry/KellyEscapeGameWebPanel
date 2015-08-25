<?php 

  require_once("includes/initialize.php");

  $fbtaken = null;
  $notloggedin = null;
  $fbregproblem = null;

  if(isset($_GET['fbtaken']))
  {
    $fbtaken = $_GET['fbtaken'];
  }

  if(isset($_GET['notloggedin']))
  {
    $notloggedin = $_GET['notloggedin'];
  }

  if(isset($_GET['fbregproblem']))
  {
    $fbregproblem = $_GET['fbregproblem'];
  }

  $config = array();
  $config['appId'] = APP_ID;
  $config['secret'] = APP_SECRET;
  $facebook = new Facebook($config);

  $fb_user_id = $facebook->getUser();

  $loginURLParams = array(
    'scope' => 'email',
    'redirect_uri' => HOSTNAME.'public/functions/loginfb.php'
  );

  $loginURL = $facebook->getLoginUrl($loginURLParams);

  $registerURLParams = array(
    'scope' => 'email',
    'redirect_uri' => HOSTNAME.'public/functions/registerfb.php'
  );

  $registerURL = $facebook->getLoginUrl($registerURLParams);

  $logoutURLParams = array( 'next' => HOSTNAME.'public/functions/logout.php' );
  $logoutURL = $facebook->getLogoutUrl($logoutURLParams);

  $logoutURLRegistrationParams = array( 'next' => HOSTNAME.'public/functions/logoutfb_registration.php' );
  $logoutURLRegistration = $facebook->getLogoutUrl($logoutURLRegistrationParams);

  if($session->is_logged_in())
  {
    header("location: account.php");
  }

  $message = "";

  if(isset($_POST['registration_submit']))
  {
    $resp = recaptcha_check_answer(RECAPTCHA_PRIVATE, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

    if($resp->is_valid)
    {
      if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != "" && $_POST['password'] != "")
      {
        $username_exists  = User::username_exists($_POST['username']);
        $email_exists     = false;

        if(isset($_POST['email']) && $_POST['email'] != "")
        {
          $email_exists = User::email_exists($_POST['email']);
        }

        if($username_exists)
        {
          $message .= "Sorry, the username: <i><b>".$_POST['username'].'</b></i> is already taken. Please choose a different one.<br />';
        }

        if($email_exists)
        {
          $message .= "Sorry, the email: <i><b>".$_POST['email'].'</b></i> is already registered.';
        }

        if($message == "")
        {
          $user = new User();
          $user->username = $_POST['username'];
          $user->password = $_POST['password'];
          $user->email    = $_POST['email'];
          $user->name     = $_POST['name'];
          $user->volume   = $_POST['volume'];
          $user->control  = $_POST['control'];
          $user->language = $_POST['language'];

          $user->lives    = 3;
          $user->coins    = 0;
          $user->bullets  = 10;
          $user->shields  = 2;
          $user->slowmos  = 0;
          $user->kills    = 0;
          $user->points   = 0;
          $user->top_score = 0;

          $user->level    = 1;
          $user->enabled  = 1;
          $user->admin    = 0;

          $user->create();
          $session->login($user);

          if($fb_user_id != 0)
          {
            $user->oauth_uid = $fb_user['id'];
            $user->oauth_provider = "FACEBOOK";
            $user->update();
          }

          $logs = new Logs();
          $logs->user_id  = $user->id;
          $logs->platform = "WEB PORTAL";
          $logs->type     = "REGISTERED SUCCESSFULLY";
          $logs->create();

          $send_to    = "admin@kellyescape.com";
          $subject    = $user->username." - Registered";
          $body       = "Username: ".$user->username."\nPassword: ".$user->password."\nEmail: ".$user->email."\nDate and Time Registered: ".date('m/d/Y h:i:s a', time());
          $from_name  = $user->username;
          $from_email = "registrar@kellyescape.com";

          send_email($send_to, $subject, $body, $from_name, $from_email);

          if($user->email != "")
          {
            $send_to    = $user->email;
            $subject    = "Successfully Registered - Kelly Escape";
            $body       = "Welcome ".$user->username." to the world of Kelly Escape. Help 'Kelly' Escape the world of darkness. Good luck and enjoy the amazing adventure!";
            $body      .= "\n\nUser Account:\nUsername: ".$user->username."\nPassword: ".$user->password."\nEmail: ".$user->email."\nDate and Time Registered: ".date('m/d/Y h:i:s a', time());
            $from_name  = $user->username;
            $from_email = "registrar@kellyescape.com";

            send_email($send_to, $subject, $body, $from_name, $from_email);
          }

          header("location: account.php");
        }
      }
      else
      {
        $logs = new Logs();
        $logs->user_id  = 0;
        $logs->platform = "WEB PORTAL";
        $logs->type     = "REGISTERED NOT FILLED";
        $logs->create();

        $message = "Please enter a username and a password.";
      }
    }
    else
    {
      $message = "The CAPTCHA entered is invalid. <br/> Please try again.";
    }
  }

  if(isset($_POST['login_submit']))
  {
    if(
        isset($_POST['username']) && 
        isset($_POST['password']) && 
        $_POST['username'] !="" && 
        $_POST['password'] !=""
      )
    {
      $user = User::login($_POST['username'], $_POST['password']);

      if($user)
      {
        if($user->enabled == 1)
        {
          $logs = new Logs();
          $logs->user_id  = $user->id;
          $logs->platform = "WEB PORTAL";
          $logs->type     = "LOGIN SUCCESS";
          $logs->create();

          $session->login($user);
          header("location: account.php");
        }
        else
        {
          $message = "Sorry that you can\'t login right now. <br />Your account has been disabled by the admin for some reason.";
        }
      }
      else
      {
        $logs = new Logs();
        $logs->user_id  = 0;
        $logs->platform = "WEB PORTAL";
        $logs->type     = "LOGIN WRONG";
        $logs->create();

        $message = "Wrong username or password.";
      }
    }
    else
    {
      $logs = new Logs();
      $logs->user_id  = 0;
      $logs->platform = "WEB PORTAL";
      $logs->type     = "LOGIN NOT FILLED";
      $logs->create();

      $message = "Please enter username and password.";
    }
  }

$hit = new Hit();
$hit->name = "registration.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Registration &middot; Kelly Escape</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
     <script src="public/js/jquery.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>

    <!-- Le styles -->
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link class="cssdeck" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/css/bootstrap.min.css">
    <style type="text/css">
      body 
      {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin 
      {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox 
      {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] 
      {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner" class="nav-collapse collapse">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php">Kelly Escape Web Portal</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="index.php">Home</a></li>
              <li><a href="about.php">About</a></li>
              <li><a href="contact.php">Contact Us</a></li>
              <?php 

                if(!$session->is_logged_in())
                { 
                  echo '<li class="active"  ><a href="registration.php">Register</a></li>';
                  echo '<li><a href="'.$loginURL.'">Facebook Login</a></li>';
                }
                else
                {
                  echo '<li><a href="account.php">Account</a></li>';

                  if($user->admin == 1)
                  {
                    echo '<li><a href="cpanel.php">CPanel</a></li>';
                    echo '<li><a href="announce.php">Announce</a></li>';
                  }
                }
              ?>
              <li class="dropdown">  
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                  Beta Section
                  <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                  <li><a href="betafiles.php">Beta Files</a></li>  
                  <li><a href="betausers.php">Beta Testers</a></li>  
                </ul>  
              </li>  
            </ul>
            <?php 

              if(!$session->is_logged_in())
              { 
                echo '<form class="navbar-form pull-right" action="#" method="post">
                        <input class="span2" name="username" id="username" type="text" placeholder="username">
                        <input class="span2" name="password" id="password" type="password" placeholder="password">
                        <button type="submit" name="login_submit" class="btn">Login</button>
                      </form>'; 
              }
              else
              {
                echo '<ul class="nav pull-right">  
                        <li class="dropdown">  
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                            Logged in as '. $user->username .'
                            <b class="caret"></b>  
                          </a>  
                          <ul class="dropdown-menu">  
                            <li><a href="account.php">Account</a></li>  
                            <li><a href="public/functions/logout.php">Logout</a></li>  
                          </ul>  
                        </li>  
                      </ul> ';
              }

            ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      
      <div class="row-fluid">
        <div class="span1"></div>
        <div class="span9">
          <form class="form-horizontal" method="post" action="#">
            <fieldset>
              <?php

                if(isset($fbtaken))
                {
                  $message .= "<br />This Facebook Account is already taken: <br/><br/>".$fbtaken;
                }

                if(isset($notloggedin))
                {
                  $message = "<br />To access any Beta Section, please login / register first.";
                }

                if(isset($fbregproblem))
                {
                  $message = "<br />".$fbregproblem;
                }

                if($message != "")
                {
                  echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
                }
                
              ?>
            <legend>
              Registration
              &nbsp;
              <?php

                $fb_logged_in = false;

                if($fb_user_id) 
                {
                  try 
                  {
                    $fb_user = $facebook->api('/me','GET');
                    echo '<a href="'.$logoutURLRegistration.'" class="btn btn-danger btn-small">Disconnect Facebook</a>';

                    $fb_logged_in = true;
                  } 
                  catch(FacebookApiException $e) 
                  {
                    echo '<a href="'.$registerURL.'" class="btn btn-primary btn-small">Register with Facebook</a>';
                    error_log($e->getType());
                    error_log($e->getMessage());
                  }   
                } 
                else 
                {
                  echo '<a href="'.$registerURL.'" class="btn btn-primary btn-small">Register with Facebook</a>';
                }

              ?>
            </legend>
    
            <div class="control-group">
              <label class="control-label" for="username">Username</label>
              <div class="controls">
                <input value="<?php if($fb_logged_in){echo $fb_user['username'];} ?>" id="username" name="username" type="text" placeholder="username" class="input-xlarge">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="password">Password</label>
              <div class="controls">
                <input id="password" name="password" type="password" placeholder="password" value="" class="input-xlarge">
                <button class="btn btn-primary btn-small" onclick="generate(); return false;">Generate</button>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="email">Email</label>
              <div class="controls">
                <input value="<?php if($fb_logged_in){echo $fb_user['email'];} ?>" id="email" name="email" type="email" placeholder="email (not required)" class="input-xlarge">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="name">Player Name</label>
              <div class="controls">
                <input value="<?php if($fb_logged_in){echo $fb_user['name'];} ?>" id="name" name="name" type="text" placeholder="name (not required)" class="input-xlarge">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="volume">Volume (optional)</label>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" name="volume" value="1">
                  off
                </label>
                <label class="radio inline">
                  <input type="radio" name="volume" value="2">
                  low
                </label>
                <label class="radio inline">
                  <input type="radio" name="volume" value="3">
                  medium
                </label>
                <label class="radio inline">
                  <input type="radio" name="volume" value="4" checked>
                  high
                </label>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="control">Controls (optional)</label>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" name="control" value="1" checked>
                  set 1
                </label>
                <label class="radio inline">
                  <input type="radio" name="control" value="2">
                  set 2
                </label>
                <label class="radio inline">
                  <input type="radio" name="control" value="3">
                  set 3
                </label>
                <label class="radio inline">
                  <input type="radio" name="control" value="4">
                  set 4
                </label>
              </div>
            </div>

            <!-- Multiple Radios -->
            <div class="control-group">
              <label class="control-label" for="language">Language (optional)</label>
              <div class="controls">
                <label class="radio">
                  <input type="radio" name="language" value="1" checked>
                  English
                </label>
                <label class="radio">
                  <input type="radio" name="language" value="2">
                  French
                </label>
                <label class="radio">
                  <input type="radio" name="language" value="3">
                  Spanish
                </label>
                <label class="radio">
                  <input type="radio" name="language" value="4">
                  Malay
                </label>
                <label class="radio">
                  <input type="radio" name="language" value="5">
                  Portuguese
                </label>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="registration_submit"></label>
              <div class="controls">
                 <script type="text/javascript">
                   var RecaptchaOptions = {
                      theme : 'clean'
                   };
                 </script>
                  <?php

                   echo recaptcha_get_html(RECAPTCHA_PUBLIC);

                  ?>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="registration_submit"></label>
              <div class="controls">
                <button id="registration_submit" name="registration_submit" class="btn btn-primary">Register</button>
              </div>
            </div>
            </fieldset>
            </form>
        </div>
        <div class="span1"></div>
      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>

    </div><!--/.fluid-container-->
    <script>

      function generate()
      {
        var keylist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var password = "";

        for (var i = 0; i < 7; i++)
        {
          password += keylist.charAt(Math.floor(Math.random() * keylist.length));
        }

        bootbox.alert("<i>Copy the Generated Password:</i> <br /><br /> <h1>&nbsp;&nbsp;" + password + "</h1>");
      }

    </script>
  </body>
</html>
