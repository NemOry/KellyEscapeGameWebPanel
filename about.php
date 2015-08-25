<?php 

  require_once("includes/initialize.php");

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

  $logoutURLParams = array( 'next' => HOSTNAME.'public/functions/logout.php' );
  $logoutURL = $facebook->getLogoutUrl($logoutURLParams);

  $message = "";

  if($session->is_logged_in())
  {
    $user = User::get_by_id($session->user_id);
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
$hit->name = "about.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>About &middot; Kelly Escape</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="public/js/jquery.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body 
      {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav 
      {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right 
        {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
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
              <li class="active"><a href="about.php">About</a></li>
              <li><a href="contact.php">Contact Us</a></li>
              <?php 

              if(!$session->is_logged_in())
              { 
                echo '<li><a href="registration.php">Register</a></li>';
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

    <?php

    if($message != "")
    {
      echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
    }
    
    ?>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span1"></div>
        
        <div class="span1"></div>
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>

    </div><!--/.fluid-container-->
  </body>
</html>
