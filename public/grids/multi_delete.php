<?php 

require_once("../../includes/initialize.php");

$what = $_POST['what'];
$ids = $_POST['ids'];

$response = "error";

global $session;

if(!$session->is_logged_in())
{
	die("not logged in");
}

if($what == "user")
{
	foreach ($ids as $id) 
	{
		User::get_by_id($id)->delete();
	}

	$response = "success";
}
else if($what == "code")
{
	foreach ($ids as $id) 
	{
		Code::get_by_id($id)->delete();
	}

	$response = "success";
}
else if($what == "redeemed_code")
{
	foreach ($ids as $id) 
	{
		RedeemedCode::get_by_id($id)->delete();
	}

	$response = "success";
}
else if($what == "log")
{
	foreach ($ids as $id) 
	{
		Logs::get_by_id($id)->delete();
	}

	$response = "success";
}
else if($what == "hit")
{
	foreach ($ids as $id) 
	{
		Hit::get_by_id($id)->delete();
	}

	$response = "success";
}

echo $response;

?>