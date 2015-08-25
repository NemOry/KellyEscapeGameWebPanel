<?php 

require_once("../initialize.php");

if(isset($_GET["username"]) && isset($_GET["password"]))
{
	$user = new User();
	$user->username 	= $_GET["username"];
	$user->password 	= $_GET["password"];
	$user->email		= $_GET["email"];
	$user->level 		= $_GET["level"];
	$user->name 		= $_GET["name"];
	$user->lives 		= $_GET["lives"];
	$user->coins 		= $_GET["coins"];
	$user->bullets 		= $_GET["bullets"];
	$user->shields 		= $_GET["shields"];
	$user->slowmos 		= $_GET["slowmos"];
	$user->kills 		= $_GET["kills"];
	$user->points 		= $_GET["points"];
	$user->top_score 	= $_GET["top_score"];
	$user->volume 		= $_GET["volume"];
	$user->control 		= $_GET["control"];
	$user->language 	= $_GET["language"];
	
	if(isset($_GET["name"]) && $_GET["name"] != "")
	{
		$user->name = $_GET["name"];
	}
	else
	{
		$user->name = "Kelly";
	}
	
	$username_exists = false;
	$email_exists = false;

	if(User::username_exists($user->username))
	{
		$username_exists = true;
	}

	if(isset($_GET["email"]))
	{
		if(User::email_exists($_GET["email"]))
		{
			$email_exists = true;
		}
		else
		{
			$user->email = $_GET["email"];
		}
	}
	else
	{
		$user->email = "";
	}

	if($email_exists == true && $username_exists == true)
	{
		echo "username_and_email_exists";
	}
	else if($email_exists == true)
	{
		echo "email_exists";
	}
	else if($username_exists == true)
	{
		echo "username_exists";
	}
	else
	{
		$user->create();

		$logs = new Logs();
      	$logs->user_id  = User::get_by_username($user->username)->id;
      	$logs->platform = $_GET["platform"];
      	$logs->type     = "REGISTERED";
      	$logs->create();
		
		$send_to    = "nemoryoliver@gmail.com";
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
		echo "success";
	}
}
else
{
	echo "error";
}

?>