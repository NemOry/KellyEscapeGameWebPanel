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

  if(!$session->is_logged_in())
  {
    header("location: registration.php?notloggedin");
  }
  else
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
$hit->name = "betafiles.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Beta Files &middot; Kelly Escape</title>
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
              <li  class="dropdown active">  
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                  Beta Section
                  <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                  <li class="active"><a href="betafiles.php">Beta Files</a></li>  
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

    <div class="container-fluid" >
      <div class="row-fluid" >
        <div class="span1"></div>
        <div class="span6">
          <legend>Kelly Escape Beta Files &nbsp;<a href="contact.php?frombeta" class="btn btn-danger">Report a Problem</a></legend>
          <table class="table table-striped">
              <thead>
                <tr>
                  <th>BlackBerry 10 - SKU: <i>KellyEscapeBeta</i></th>
                  <th>Link</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>BlackBerry Z10 / Dev Alpha A & B</td>
                  <td>
                    <a href="http://sdrv.ms/15a4jdk">
                      <button class="btn btn-info btn-mini">Download v0.2</button>
                    </a>
                    <a href="http://sdrv.ms/14CD8Yv">
                      <button class="btn btn-info btn-mini">Download v0.1</button>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>BlackBerry Q10 / Q5 / Dev Alpha C</td>
                  <td>
                    <a href="http://sdrv.ms/12Dgijr">
                      <button class="btn btn-info btn-mini">Download v0.1</button>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>iOS</th>
                  <th>Link</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>iPhone 5</td>
                  <td>
                    <button class="btn btn-info btn-mini disabled">Download</button> (in the works)
                  </td>
                </tr>
                <tr>
                  <td>iPad Mini</td>
                  <td>
                    <button class="btn btn-info btn-mini disabled">Download</button> (in the works)
                  </td>
                </tr>
                <tr>
                  <td>iPad 2 - 4</td>
                  <td>
                    <button class="btn btn-info btn-mini disabled">Download</button> (in the works)
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Windows Phone 8</th>
                  <th>Link</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Any Windows Phone 8+</td>
                  <td>
                    <button class="btn btn-info btn-mini disabled">Download</button> (in the works)
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Android</th>
                  <th>Link</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Any Android Gingerbread+</td>
                  <td>
                    <button class="btn btn-info btn-mini disabled">Download</button> (in the works)
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
        <div class="span1"></div>
      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>

    </div><!--/.fluid-container-->
  </body>
</html>
