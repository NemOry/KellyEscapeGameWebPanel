<?php 

require_once("includes/initialize.php");

if(!$session->is_logged_in())
{
   header("location: registration.php?notloggedin");
}

$user = User::get_by_id($session->user_id);

$message = "";

$hit = new Hit();
$hit->name = "betausers.php";
$hit->platform = "WEB PORTAL";
$hit->user_id = ($session->is_logged_in() == true ? $session->user_id : 0);
$hit->create();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Beta Testers &middot; Kelly Escape</title>
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
    <script>

      $(function()
      {
        function volumeFormat( cellvalue, options, rowObject )
        {
          if(cellvalue == 1)
          {
            return "OFF";
          }
          else if(cellvalue == 2)
          {
            return "LOW";
          }
          else if(cellvalue == 3)
          {
            return "MEDIUM";
          }
          else if(cellvalue == 4)
          {
            return "HIGH";
          }
        }

        function controlFormat( cellvalue, options, rowObject )
        {
          if(cellvalue == 1)
          {
            return "SET 1";
          }
          else if(cellvalue == 2)
          {
            return "SET 2";
          }
          else if(cellvalue == 3)
          {
            return "SET 3";
          }
          else if(cellvalue == 4)
          {
            return "SET 4";
          }
        }

        function languageFormat( cellvalue, options, rowObject )
        {
          if(cellvalue == 1)
          {
            return "ENGLISH";
          }
          else if(cellvalue == 2)
          {
            return "FRENCH";
          }
          else if(cellvalue == 3)
          {
            return "SPANISH";
          }
          else if(cellvalue == 4)
          {
            return "MALAY";
          }
          else if(cellvalue == 5)
          {
            return "PORTUGUESE";
          }
        }


        var last_clicked_id = 0;
        var lastSel = 0;

        jQuery("#grid_betausers").jqGrid({
            url:'public/grids/betausers_xml.php',
            datatype: 'xml',
            mtype: 'GET',
            colNames:[
            'USERNAME', 
            'NAME', 
            'LEVEL', 
            'LIVES', 
            'BULLETS', 
            'COINS', 
            'SHIELDS', 
            'KILLS', 
            'SLOWMOS', 
            'POINTS', 
            'TOP SCORE', 
            'DATE', 
            'VOL', 
            'CTRL', 
            'LANG'
            ],
            colModel :[ 
              {name:'username', index:'username', width:10, align:'left', sortable:true, editable:false, search:true},
              {name:'name', index:'name', width:10, align:'left', sortable:true, editable:false, search:true},
              {name:'level', index:'level', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'lives', index:'lives', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'bullets', index:'bullets', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'coins', index:'coins', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'shields', index:'shields', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'kills', index:'kills', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'slowmos', index:'slowmos', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'points', index:'points', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'top_score', index:'top_score', width:5, align:'left', sortable:true, editable:false, search:true},
              {name:'date', index:'date', width:10, align:'left', sortable:true, editable:false, search:true},
              {name:'volume', index:'volume', width:5, align:'left', sortable:true, editable:false, search:true, formatter:volumeFormat},
              {name:'control', index:'control', width:5, align:'left', sortable:true, editable:false, search:true, formatter:controlFormat},
              {name:'language', index:'language', width:5, align:'left', sortable:true, editable:false, search:true, formatter:languageFormat}
            ],
            width: 1290,
            height: 400,
            pager: '#nav_betausers',
            rowNum:30,
            rowList:[10,20,30,40,50,100,200,300,400,500],
            sortname: 'id',
            sortorder: 'desc', 
            viewrecords: true,
            gridview: true,
            caption: 'Beta Testers'
        });

      jQuery("#grid_betausers").jqGrid('navGrid','#nav_betausers');
    });

    </script>
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

              echo '<li><a href="account.php">Account</a></li>';

              if($user->admin == 1)
              {
                echo '<li><a href="cpanel.php">CPanel</a></li>';
                echo '<li><a href="announce.php">Announce</a></li>';
              }

              ?>
              <li class="dropdown active">  
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                  Beta Section
                  <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                  <li><a href="betafiles.php">Beta Files</a></li>  
                  <li class="active"><a href="betausers.php">Beta Testers</a></li>  
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

        if($message != "")
        {
          echo "<script>bootbox.alert('<i>".$message."</i>');</script>";
        }

        ?>
        <div class="span1"></div>
          <table id="grid_betausers"><tr><td/></tr></table> 
          <div id="nav_betausers"></div>
        <div class="span1"></div>

      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Nemory Development Studios 2013</p>
      </footer>
    </div><!--/.fluid-container-->
  </body>
</html>
