<?php 

require_once("../initialize.php");

if(isset($_GET["username"]) && isset($_GET["password"]))
{
	if(User::authenticate(trim($_GET["username"]), trim($_GET["password"])))
	{
		$user = User::login(trim($_GET["username"]), trim($_GET["password"]));

		$logs = new Logs();
		$logs->user_id 	= $user->id;
		$logs->platform = $_GET["platform"];
		$logs->type 	= "LOGIN SUCCESS";
		$logs->create();

		$json = "[" . json_encode($user) . "]";
		echo addslashes($json);
	}
	else
	{
		echo "false";
	}
}
else
{
	echo "error";
}

?>