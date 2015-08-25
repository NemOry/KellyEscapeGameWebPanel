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
  
  if(isset($_POST["code"]))
  {
    if(Code::code_exists($_POST["code"]))
    {
      $code = Code::get_by_code($_POST["code"]);

      if($code->user_id != 0) // specific user redeem
      {
        if($code->user_id == $user->id)
        {
          if(!redeemed($code, $user))
          {
            $logs = new Logs();
            $logs->user_id  = $user->id;
            $logs->platform = "WEB PORTAL";
            $logs->type     = "REDEEMED SUCCESSFULLY: ".$code->code;
            $logs->create();

            echo "success:::".$code->message;    
          }
          else
          {
            $logs = new Logs();
            $logs->user_id  = $user->id;
            $logs->platform = "WEB PORTAL";
            $logs->type     = "REDEEMED ALREADY: ".$code->code;
            $logs->create();

            echo "redeemed";
          }
        }
        else
        {
          $logs = new Logs();
          $logs->user_id  = $user->id;
          $logs->platform = "WEB PORTAL";
          $logs->type     = "REDEEMED NOT OWNED: ".$code->code;
          $logs->create();

          echo "not_yours";
        }
      }
      else // global redeem
      {
        if(!redeemed($code, $user))
        {
          $logs = new Logs();
          $logs->user_id  = $user->id;
          $logs->platform = "WEB PORTAL";
          $logs->type     = "REDEEMED SUCCESSFULLY: ".$code->code;
          $logs->create();

          echo "success:::".$code->message;    
        }
        else
        {
          $logs = new Logs();
          $logs->user_id  = $user->id;
          $logs->platform = "WEB PORTAL";
          $logs->type     = "REDEEMED ALREADY: ".$code->code;
          $logs->create();

          echo "redeemed";
        }
      } 
    }
    else
    {
      $logs = new Logs();
      $logs->user_id  = $user->id;
      $logs->platform = "WEB PORTAL";
      $logs->type     = "REDEEMED NOT EXIST: ".$code->code;
      $logs->create();

      echo "not_exist";
    }
  }
  else
  {
    echo "error";
  }
}

function redeemed($code, $user)
{
  if(!RedeemedCode::exists($code->id, $user->id))
  {
    if ($code->item != "")
    {
      if ($code->item == "life")
      {
          $user->lives += $code->value;
      }
      else if ($code->item == "bullet")
      {
          $user->bullets += $code->value;
      }
      else if ($code->item == "coin")
      {
          $user->coins += $code->value;
      }
      else if ($code->item == "shield")
      {
         $user->shields += $code->value;
      }
      else if ($code->item == "slowmo")
      {
         $user->slowmos += $code->value;
      }
    }

    $user->update();

    $redeemed_code = new RedeemedCode();
    $redeemed_code->code_id = $code->id;
    $redeemed_code->user_id = $user->id;
    $redeemed_code->create();

    return false;
  }
  else
  {
    return true;
  }
}

?>