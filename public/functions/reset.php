<?php

  require_once("../../includes/initialize.php");

  global $session;

  if(!$session->is_logged_in())
  {
      redirect_to("../../index.php");
  }
  else
  {
  	$user = User::get_by_id($session->user_id);
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
    $user->update();

    $logs = new Logs();
    $logs->user_id  = $session->id;
    $logs->platform = "WEB PORTAL";
    $logs->type     = "RESET";
    $logs->create();

    echo "success";
  }

?>