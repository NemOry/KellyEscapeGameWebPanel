<?php 

  require_once("includes/initialize.php");

  $frombeta = null;

  if(isset($_GET['frombeta']))
  {
    $frombeta = $_GET['frombeta'];
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

  if(isset($_POST['btnsend']))
  {
    if(
      isset($_POST['email']) && 
      isset($_POST['username']) && 
      isset($_POST['message']) && 
      isset($_POST['subject']) &&
      isset($_POST['name'])
      )
    {
      $send_to    = "support@kellyescape.com";
      $subject    = $_POST['subject'];
      $body       = $_POST['message'];
      $from_name  = $_POST['username'].", ".$_POST['name'];
      $from_email = $_POST['email'];

      send_email($send_to, $subject, $body, $from_name, $from_email);

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
      $logs->type     = "SENT EMAIL";
      $logs->create();

      $message = "Your message has been sent to our Customer Support Team. We will try our best to reply no later than 24 hours. Thank you.";
    }
    else
    {
      $message = "All the fields are required. Please fill them all in.";
    }
  }

$hit = new Hit();
$hit->name = "contact.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Contact Us &middot; Kelly Escape</title>
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
    
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=170997829744867";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

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
              <li class="active"><a href="contact.php">Contact Us</a></li>
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

    <div class="container-fluid">
      <div class="row-fluid">
        <?php

        if($message != "")
        {
          echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
        }
        
        ?>
        <div class="span1"></div>
        <div class="span5">
        
        	<form class="form-horizontal" method="post" action="#">
      			<fieldset>

      			<!-- Form Name -->
      			<legend>Contact Us &nbsp;
              <span class="label label-info">nemoryoliver@gmail.com</span>
              <span class="label label-important">(+63)9467595887</span>
            </legend>

      			<div class="span4">

      				<!-- Text input-->
      				<div class="control-group">
      				  <label class="control-label" for="name">Player Name</label>
      				  <div class="controls">
      				    <input <?php if($session->is_logged_in()){echo "readonly";} ?> id="name" name="name" type="text" placeholder="name"  value="<?php if($session->is_logged_in()){echo $user->name;} ?>" class="input-xlarge">
      				  </div>
      				</div>

      				<!-- Text input-->
      				<div class="control-group">
      				  <label class="control-label" for="username">Username</label>
      				  <div class="controls">
      				    <input <?php if($session->is_logged_in()){echo "readonly";} ?> id="username" name="username" type="text" placeholder="username" value="<?php if($session->is_logged_in()){echo $user->username;} ?>" class="input-xlarge">
      				  </div>
      				</div>

      				<!-- Text input-->
      				<div class="control-group">
      				  <label class="control-label" for="email">Email</label>
      				  <div class="controls">
      				    <input <?php if($session->is_logged_in()){echo "readonly";} ?> id="email" name="email" type="text" placeholder="email" value="<?php if($session->is_logged_in()){echo $user->email;} ?>" class="input-xlarge">
      				  </div>
      				</div>

      				<!-- Text input-->
      				<div class="control-group">
      				  <label class="control-label" for="subject">Subject</label>
      				  <div class="controls">
      				    <input value="<?php if(isset($frombeta)){echo 'Beta Problem Report';} ?>" id="subject" name="subject" type="text" placeholder="subject" class="input-xlarge">
      				  </div>
      				</div>

      			</div>

      			<div class="span8">

      				<div class="control-group">
      				  <label class="control-label" for="message">Message</label>
      				  <div class="controls">                     
      				    <textarea id="message" name="message" class="span8" style="width:285px; height:100px"></textarea>
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
        </div>

        <div class="span3">
          <br /><br />
          <!-- FACEBOOK -->
          <div class="fb-comments" data-width="470" data-num-posts="10" data-colorscheme="light"></div>
          <!-- TWITTER -->
          <a class="twitter-timeline" href="https://twitter.com/NemOry" data-widget-id="349453759086206976">Tweets by @NemOry</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <div class="span1"></div>

      </div><!--/row-->
    </div><!--/.fluid-container-->

    <hr>

      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>
  </body>
</html>
