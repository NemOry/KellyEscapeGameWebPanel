<?php 

require_once("../../includes/initialize.php");

$ids = $_POST['ids'];

$response = "error";

global $session;

if(!$session->is_logged_in())
{
	die("not logged in");
}

foreach ($ids as $id) 
{
	$user = User::get_by_id($id);
	$user->enabled = 1;
	$user->update();
}

echo "success";

?>