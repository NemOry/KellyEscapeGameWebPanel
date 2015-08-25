<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$redeemed_code 			= new RedeemedCode();
	$redeemed_code->code_id 	= $_POST['code_id'];
	$redeemed_code->user_id 	= $_POST['user_id'];
	$redeemed_code->create();

}
else if($_POST['oper']=='edit')
{
	$redeemed_code 				= RedeemedCode::get_by_id($_POST['id']);
	$redeemed_code->code_id 	= $_POST['code_id'];
	$redeemed_code->user_id 	= $_POST['user_id'];
	$redeemed_code->update();
}
else if($_POST['oper']=='del')
{
	RedeemedCode::get_by_id($_POST['id'])->delete();
}

?>