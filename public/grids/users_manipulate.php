<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$user = new User();
	$user->username 	= $_POST['username'];
	$user->password 	= $_POST['password'];
	$user->email 		= $_POST['email'];
	$user->name 		= $_POST['name'];
	$user->level 		= $_POST['level'];
	$user->lives 		= $_POST['lives'];
	$user->bullets 		= $_POST['bullets'];
	$user->coins 		= $_POST['coins'];
	$user->shields 		= $_POST['shields'];
	$user->kills 		= $_POST['kills'];
	$user->slowmos 		= $_POST['slowmos'];
	$user->points 		= $_POST['points'];
	$user->top_score 	= $_POST['top_score'];
	$user->date 		= $_POST['date'];
	$user->volume 		= $_POST['volume'];
	$user->control 		= $_POST['control'];
	$user->language 	= $_POST['language'];
	$user->enabled 		= $_POST['enabled'];
	$user->admin 		= $_POST['admin'];
	$user->create();

}
else if($_POST['oper']=='edit')
{
	$user = User::get_by_id($_POST['id']);
	$user->username 	= $_POST['username'];
	$user->password 	= $_POST['password'];
	$user->email 		= $_POST['email'];
	$user->name 		= $_POST['name'];
	$user->level 		= $_POST['level'];
	$user->lives 		= $_POST['lives'];
	$user->bullets 		= $_POST['bullets'];
	$user->coins 		= $_POST['coins'];
	$user->shields 		= $_POST['shields'];
	$user->kills 		= $_POST['kills'];
	$user->slowmos 		= $_POST['slowmos'];
	$user->points 		= $_POST['points'];
	$user->top_score 	= $_POST['top_score'];
	$user->date 		= $_POST['date'];
	$user->volume 		= $_POST['volume'];
	$user->control 		= $_POST['control'];
	$user->language 	= $_POST['language'];
	$user->enabled 		= $_POST['enabled'];
	$user->admin 		= $_POST['admin'];
	$user->update();
}
else if($_POST['oper']=='del')
{
	if($_POST['id'] != $session->user_id)
	{
		User::get_by_id($_POST['id'])->delete();
	}
}

?>