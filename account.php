<?php 

require_once("includes/initialize.php");

$fbtaken = null;
$disconnected = null;

if(isset($_GET['fbtaken']))
{
  $fbtaken = $_GET['fbtaken'];
}

if(isset($_GET['disconnected']))
{
  $disconnected = $_GET['disconnected'];
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

$connectURLParams = array(
  'scope' => 'email',
  'redirect_uri' => HOSTNAME.'public/functions/connectfb.php'
);

$connectURL = $facebook->getLoginUrl($connectURLParams);

$logoutURLParams = array( 'next' => HOSTNAME.'public/functions/logout.php' );
$logoutURL = $facebook->getLogoutUrl($logoutURLParams);

$disconnectURLParams = array('next' => HOSTNAME.'public/functions/disconnectfb.php');
$disconnectURL = $facebook->getLoginUrl($disconnectURLParams);

if(!$session->is_logged_in())
{
  header("location: index.php");
}

$user = User::get_by_id($session->user_id);

$message = "";
$confirmDisconnect = "";

if(isset($_POST['save']))
{
  if( 
      $_POST["username"] != "" && 
      $_POST["password"] != "" && 
      $_POST["email"] != "" && 
      $_POST["name"] != ""
    )
  {
    $user->password = $_POST["password"];
    $user->email    = $_POST["email"];
    $user->name     = $_POST["name"];
    $user->volume   = $_POST["volume"];
    $user->control  = $_POST["control"];
    $user->language = $_POST["language"];

    if($_POST["username"] != $user->username)
    {
      $user->username = $_POST["username"];

      if(!User::username_exists($user->username))
      {
        $logs = new Logs();
        $logs->user_id  = $user->id;
        $logs->platform = "WEB PORTAL";
        $logs->type     = "UPDATED ACCOUNT";
        $logs->create();

        $user->update();
        $message = "Successfully saved.";
      }
      else
      {
         $message = "Username already exists.";
      }
    }
    else
    {
      $logs = new Logs();
      $logs->user_id  = $user->id;
      $logs->platform = "WEB PORTAL";
      $logs->type     = "UPDATED ACCOUNT";
      $logs->create();

      $user->update();
      $message = "Successfully saved.";
    }
  }
  else
  {
    $message = "All fields are required.";
  } 
}
else if(isset($_POST['reset']))
{
  $message = "Successfully reset.";
}

$hit = new Hit();
$hit->name = "account.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>User Account &middot; Kelly Escape</title>
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

              echo '<li class="active"><a href="account.php">Account</a></li>';

              if($user->admin == 1)
              {
                echo '<li><a href="cpanel.php">CPanel</a></li>';
                echo '<li><a href="announce.php">Announce</a></li>';
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
            <ul class="nav pull-right">  
              <li class="dropdown">  
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                  Logged in as <?php echo $user->username; ?>  
                  <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                  <li><a href="account.php">Account</a></li>  
                  <li><a href="public/functions/logout.php">Logout</a></li>  
                </ul>  
              </li>  
            </ul>  
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <?php

        if(isset($fbtaken))
        {
          $confirmDisconnect .= "<br />Another user is already using your Facebook Account: <br/><br/>".$fbtaken;
          $confirmDisconnect .= "<br /><br />Suggestion: You can use another Facebook Account you have. <br/>To do this, logout in facebook and try connecting again.";
          echo "<script>bootbox.alert('".$confirmDisconnect."');</script>";
        }

        if($message != "")
        {
          echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
        }

        if(isset($disconnected))
        {
          echo "<script>bootbox.alert('Facebook account successfully logged out.');</script>";
        }
        
        ?>
        <div class="span1"></div>
        <div class="span5">
          <form class="form-horizontal" action="#" method="post">
            <fieldset>
            <legend>
              Player Profile
              &nbsp;
              <?php

                if($user->oauth_uid != "") 
                {
                  echo '<button class="btn btn-danger btn-small" onclick="disconnect(); return false;">Disonnect Facebook Account</button>';
                } 
                else 
                {
                  echo '<button class="btn btn-primary btn-small" onclick="connect(); return false;">Connect Facebook Account</button>';
                }

              ?>
            </legend>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="name">Player Name</label>
              <div class="controls">
                <input id="name" name="name" type="text" placeholder="name" class="input-large" value="<?php echo $user->name; ?>">
              </div>
            </div>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="username">Username</label>
              <div class="controls">
                <input id="username" name="username" type="text" placeholder="username" class="input-large"  value="<?php echo $user->username; ?>">
              </div>
            </div>

            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="password">Password</label>
              <div class="controls">
                <input id="password" name="password" type="password" placeholder="password" class="input-large" value="<?php echo $user->password; ?>">
              </div>
            </div>

            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="email">Email</label>
              <div class="controls">
                <input id="email" name="email" type="email" placeholder="email" class="input-large"  value="<?php echo $user->email; ?>">
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="control-group">
              <label class="control-label" for="volume">Volume</label>
              <div class="controls">
                <label class="radio inline" for="volume-0">
                  <input type="radio" name="volume" id="volume-0" value="1" <?php if($user->volume == 1){ echo 'checked="checked"'; } ?> >
                  off
                </label>
                <label class="radio inline" for="volume-1">
                  <input type="radio" name="volume" id="volume-1" value="2" <?php if($user->volume == 2){ echo 'checked="checked"'; } ?>>
                  low
                </label>
                <label class="radio inline" for="volume-2">
                  <input type="radio" name="volume" id="volume-2" value="3" <?php if($user->volume == 3){ echo 'checked="checked"'; } ?>>
                  medium
                </label>
                <label class="radio inline" for="volume-3">
                  <input type="radio" name="volume" id="volume-3" value="4" <?php if($user->volume == 4){ echo 'checked="checked"'; } ?>>
                  high
                </label>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="control">Controls</label>
              <div class="controls">
                <label class="radio inline" for="control-0">
                  <input type="radio" name="control" id="control-0" value="1" <?php if($user->control == 1){ echo 'checked="checked"'; } ?> >
                  set 1
                </label>
                <label class="radio inline" for="control-1">
                  <input type="radio" name="control" id="control-1" value="2" <?php if($user->control == 2){ echo 'checked="checked"'; } ?>>
                  set 2
                </label>
                <label class="radio inline" for="control-2">
                  <input type="radio" name="control" id="control-2" value="3" <?php if($user->control == 3){ echo 'checked="checked"'; } ?>>
                  set 3
                </label>
                <label class="radio inline" for="control-3">
                  <input type="radio" name="control" id="control-3" value="4" <?php if($user->control == 4){ echo 'checked="checked"'; } ?>>
                  set 4
                </label>
              </div>
            </div>

            <!-- Multiple Radios -->
            <div class="control-group">
              <label class="control-label" for="language">Language</label>
              <div class="controls">
                <label class="radio" for="language-0">
                  <input type="radio" name="language" id="language-0" value="1" <?php if($user->language == 1){ echo 'checked="checked"'; } ?> >
                  English
                </label>
                <label class="radio" for="language-1">
                  <input type="radio" name="language" id="language-1" value="2" <?php if($user->language == 2){ echo 'checked="checked"'; } ?> >
                  French
                </label>
                <label class="radio" for="language-2">
                  <input type="radio" name="language" id="language-2" value="3" <?php if($user->language == 3){ echo 'checked="checked"'; } ?> >
                  Spanish
                </label>
                <label class="radio" for="language-3">
                  <input type="radio" name="language" id="language-3" value="4" <?php if($user->language == 4){ echo 'checked="checked"'; } ?> >
                  Malay
                </label>
                <label class="radio" for="language-4">
                  <input type="radio" name="language" id="language-4" value="5" <?php if($user->language == 5){ echo 'checked="checked"'; } ?> >
                  Portuguese
                </label>
              </div>
            </div>

            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="save"></label>
              <div class="controls">
                <button id="save" name="save" class="btn btn-success">Save</button>
              </div>
            </div>

            </fieldset>
            </form>
        </div>

        <div class="span5"><br/>
          <form class="form-horizontal" action="#" method="post">
            <fieldset>
            <legend>Properties</legend>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Value</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Level</td>
                  <td><span class="badge badge"><?php echo $user->level; ?></span></td>
                </tr>
                <tr>
                  <td>Top Score</td>
                  <td><span class="badge badge-success"><?php echo $user->top_score; ?></span></td>
                </tr>
                <tr>
                  <td>Coins</td>
                  <td><span class="badge badge-warning"><?php echo $user->coins; ?></span></td>
                </tr>
                <tr>
                  <td>Lives</td>
                  <td><span class="badge badge-important"><?php echo $user->lives; ?></span></td>
                </tr>
                <tr>
                  <td>Bullets</td>
                  <td><span class="badge badge-success"><?php echo $user->bullets; ?></span></td>
                </tr>
                
                <tr>
                  <td>Shields</td>
                  <td><span class="badge badge-info"><?php echo $user->shields; ?></span></td>
                </tr>
                <tr>
                  <td>Kills</td>
                  <td><span class="badge badge-important"><?php echo $user->kills; ?></span></td>
                </tr>
                <tr>
                  <td>Points</td>
                  <td><span class="badge badge-important"><?php echo $user->points; ?></span></td>
                </tr>
                <tr>
                  <td>Slowmos</td>
                  <td><span class="badge badge"><?php echo $user->slowmos; ?></span></td>
                </tr>
              </tbody>
            </table>
              <button class="btn btn-danger" onclick="confirmReset(); return false">Reset Properties</button>
              <button class="btn btn-info" onclick="redeemWithCode(); return false">Redeem with Code</button>
            </fieldset>
            </form>
        </div>

      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>
    </div><!--/.fluid-container-->
    <script>
      function redeemWithCode()
      {
        bootbox.prompt("Please enter the code.", "Cancel", "Redeem", function(inputCode) 
        {                
          if (inputCode != null && inputCode != "") 
          {                                             
            $.ajax({ 
              url: 'functions/redeem.php',
              type: 'post',
              data: {code: inputCode},
              success: function(result) 
              {
                var output      = "";
                var codeMessage = "";
                var message     = "";

                if(result.indexOf(':::') > -1)
                {
                  var splittedString = result.split(":::");
                  output = splittedString[0];
                  codeMessage = splittedString[1];
                }
                else
                {
                  output = result;
                }

                if(output == "success")
                {
                  
                  message = codeMessage;

                  if(codeMessage != "")
                  {
                    message += "<br /><br />Successfully Redeemed!<br /> CODE: " + inputCode;
                  }
                }
                else if(output == "redeemed")
                {
                  message = "Sorry, the code you entered was already redeemed.";
                }
                else if(output == "not_yours")
                {
                  message = "Sorry, the code you entered is not specific for you.";
                }
                else if(output == "not_exist")
                {
                  message = "Sorry, the code you entered does not exist.";
                }
                else if(output == "error")
                {
                  message = "Sorry, something isn't right with the server. Please try again later.";
                }
                
                bootbox.alert(message, function()
                {
                  window.location.reload();
                });
              }
            });
          }
        });
      }

      function confirmReset()
      {
        bootbox.confirm("Are you sure you want to reset all your properties?","Cancel","Reset", function(result) 
        {
          if(result)
          {
            $.ajax({ 
              url: 'functions/reset.php',
              success: function(output) 
              {
                bootbox.alert("Successfully Reset.", function()
                {
                  window.location.reload();
                });
              }
            });
          }
        }); 
      }

      function connect()
      {
        window.location.replace("<?php echo $connectURL; ?>");
      }

      function disconnect()
      {
        window.location.replace("<?php echo $disconnectURL; ?>");
      }

      function disconnectFacebook()
      {
        window.location.replace("<?php echo $disconnectURL; ?>");
      }
      
    </script>
  </body>
</html>
