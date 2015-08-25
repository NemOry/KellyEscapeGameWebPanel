<?php 

require_once("../initialize.php");

if(isset($_GET["username"]))
{
	$user = User::get_by_username($_GET["username"]);
	echo json_encode($user);
}
else
{
	echo "error";
}

?>