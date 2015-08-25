<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$log 			= new Logs();
	$log->user_id 	= $_POST['user_id'];
	$log->platform 	= $_POST['platform'];
	$log->date 		= $_POST['date'];
	$log->type 		= $_POST['type'];
	$log->create();

}
else if($_POST['oper']=='edit')
{
	$log 			= Logs::get_by_id($_POST['id']);
	$log->user_id 	= $_POST['user_id'];
	$log->platform 	= $_POST['platform'];
	$log->date 		= $_POST['date'];
	$log->type 		= $_POST['type'];
	$log->update();
}
else if($_POST['oper']=='del')
{
	Logs::get_by_id($_POST['id'])->delete();
}

?>