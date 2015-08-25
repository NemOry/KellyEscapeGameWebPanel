<?php 

require_once("../initialize.php");

$user = User::get_by_username($_GET["username"]);
echo json_encode($user);

?>