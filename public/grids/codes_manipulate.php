<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$code 			= new Code();
	$code->user_id 	= $_POST['user_id'];
	$code->code 	= $_POST['code'];
	$code->message 	= $_POST['message'];
	$code->item 	= $_POST['item'];
	$code->value 	= $_POST['value'];
	$code->create();

}
else if($_POST['oper']=='edit')
{
	$code 			= Code::get_by_id($_POST['id']);
	$code->user_id 	= $_POST['user_id'];
	$code->code 	= $_POST['code'];
	$code->message 	= $_POST['message'];
	$code->item 	= $_POST['item'];
	$code->value 	= $_POST['value'];
	$code->update();
}
else if($_POST['oper']=='del')
{
	Code::get_by_id($_POST['id'])->delete();
}

?>