<?php 

require_once("../initialize.php");

if(isset($_GET["id"]) && isset($_GET["level"]) && isset($_GET["password"]) && isset($_GET["email"]) && isset($_GET["name"]))
{
	$user = User::get_by_id($_GET["id"]);
	
	if($user != null)
	{
		$user->level 	= $_GET["level"];
		$user->password = $_GET["password"];
		$user->email 	= $_GET["email"];
		$user->name 	= $_GET["name"];
		$user->lives 	= $_GET["lives"];
		$user->bullets 	= $_GET["bullets"];
		$user->coins 	= $_GET["coins"];
		$user->shields 	= $_GET["shields"];
		$user->slowmos 	= $_GET["slowmos"];
		$user->kills 	= $_GET["kills"];
		$user->points 	= $_GET["points"];
		$user->top_score = $_GET["top_score"];
		$user->volume 	= $_GET["volume"];
		$user->control 	= $_GET["control"];
		$user->language = $_GET["language"];
		$user->update();

		$logs = new Logs();
      	$logs->user_id  = $user->id;
      	$logs->platform = $_GET["platform"];
      	$logs->type     = "UPDATED ACCOUNT";
      	$logs->create();
	
		echo "success";
	}
	else
	{
		echo "error";
	}
}
else
{
	echo "error";
}

?>