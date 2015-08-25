<?php 

require_once("../initialize.php");

if(isset($_GET["id"]))
{
	$user = User::get_by_id($_GET["id"]);
	echo "[" . json_encode($user) . "]";
}
else
{
	echo "error";
}

?>