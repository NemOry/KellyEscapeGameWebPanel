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
  else
  {
      header("location: index.php");
  }

  $message = "";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CPanel &middot; Kelly Escape</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="public/jqueryui/css/smoothness/jquery-ui-1.10.3.custom.min.css" />
    <link href="public/css/ui.jqgrid.css" rel="stylesheet" media="screen" />
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <script src="public/jqueryui/js/jquery-1.9.1.js"></script>
    <script src="public/jqueryui/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="public/js/i18n/grid.locale-en.js"></script>
    <script src="public/js/jquery.jqGrid.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>
    <style>
        #tabs { 
            background: transparent; 
            border: none; 
            font-size: 14px;
        } 
        #tabs .ui-widget-header { 
            background: transparent; 
            border: none; 
            border-bottom: 1px solid #c0c0c0; 
            -moz-border-radius: 0px; 
            -webkit-border-radius: 0px; 
            border-radius: 0px; 
        } 
        #tabs .ui-tabs-nav .ui-state-default { 
            background: transparent; 
            border: none; 
        } 
        #tabs .ui-tabs-nav .ui-state-active { 
            background: transparent url(img/uiTabsArrow.png) no-repeat bottom center; 
            border: none; 
        } 
        #tabs .ui-tabs-nav .ui-state-default a { 
            color: #858585; 
        } 
        #tabs .ui-tabs-nav .ui-state-active a { 
            color: #E01B5D; 
        }
    </style>
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
                    echo '<li class="active"><a href="cpanel.php">CPanel</a></li>';
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

        <div id="tabs" style="margin-top:10px">
            <ul>
                <li><a href="public/grids/users.php"><span>Users</span></a></li>
                <li><a href="public/grids/codes.php"><span>Codes</span></a></li>
                <li><a href="public/grids/redeemed_codes.php"><span>Redeemed Codes</span></a></li>
                <li><a href="public/grids/logs.php"><span>Logs</span></a></li>
                <li><a href="public/grids/hits.php"><span>Hits</span></a></li>
                <li><a href="public/grids/betausers.php"><span>Beta Testers</span></a></li>
            </ul>
        </div>

        <table id="grid_users"><tr><td/></tr></table> 
        <div id="nav_users"></div>
      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>
    </div><!--/.fluid-container-->
    <script>

    $('#tabs').tabs({
        load: function(event, ui) 
        {
            $(ui.panel).delegate('a', 'click', function(event) 
            {
                $(ui.panel).load(this.href);
                event.preventDefault();
            });
        }
    });

    </script>
  </body>
</html>