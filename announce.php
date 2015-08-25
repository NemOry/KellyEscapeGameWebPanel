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

  if($session->is_logged_in())
  {
      $user = User::get_by_id($session->user_id);
  }

  $message = "";

  if(isset($_POST['btnsend']))
  {
    if(
      isset($_POST['subject']) && 
      isset($_POST['message'])
      )
    {
      $subject    = $_POST['subject'];
      $body       = $_POST['message'];
      $from_name  = "Admin - Kelly Escape";
      $from_email = "admin@kellyescape.com";

      $users = User::get_by_sql("SELECT * FROM ".T_USERS." WHERE ".C_USER_EMAIL." NOT LIKE '%_@__%.__%' OR ".C_USER_EMAIL." IS NOT NULL");

      foreach ($users as $user) 
      {
        send_email($user->email, $subject, $body, $from_name, $from_email);
      }
      
      $logs = new Logs();

      if($session->is_logged_in())
      {
        $logs->user_id  = $session->user_id;
      }
      else
      {
        $logs->user_id  = 0;
      }
      
      $logs->platform = "WEB PORTAL";
      $logs->type     = "ANNOUNCED";
      $logs->create();

      $message = "Announcement sent.";
    }
    else
    {
      $message = "All the fields are required. Please fill them all in.";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Send Announcement &middot; Kelly Escape</title>
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
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <?php 

              if(!$session->is_logged_in())
              { 
                echo '<li><a href="registration.php">Register</a></li>';
              }
              else
              {
                echo '<li><a href="account.php">Account</a></li>';

                if($user->admin == 1)
                {
                  echo '<li><a href="cpanel.php">CPanel</a></li>';
                  echo '<li class="active"><a href="announce.php">Announce</a></li>';
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
      <?php

      if($message != "")
      {
        echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
      }
      
      ?>
      <div class="span1"></div>

      <form class="form-horizontal" method="post" action="#">
      	<fieldset>

      	<!-- Form Name -->
      	<legend>Send Announcements</legend>

      	<div class="span4">

      		<!-- Text input-->
      		<div class="control-group">
      		  <label class="control-label" for="subject">Subject</label>
      		  <div class="controls">
      		    <input id="subject" name="subject" type="text" placeholder="subject" class="input-xlarge">
      		  </div>
      		</div>

      	</div>

      	<div class="span8">

      		<div class="control-group">
      		  <label class="control-label" for="message">Message</label>
      		  <div class="controls">                     
      		    <textarea id="message" name="message" class="span8" rows="8"></textarea>
      		  </div>
      		</div>

      		<div class="control-group">
            <label class="control-label" for="btnsend"></label>
            <div class="controls">
              <button id="btnsend" name="btnsend" type="submit" class="btn btn-success">Send</button>
            </div>
          </div>

      	</div>

      	</fieldset>
      	</form>

        <div class="span1"></div>

      </div><!--/row-->
    </div><!--/.fluid-container-->
    <hr>

    <footer>
      <p>&copy; Nemory Development Studios 2013</p>
    </footer>
  </body>
</html>
