<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];

$users_count = User::get_by_sql("SELECT * FROM ".T_USERS);

$count = count($users_count);

if( $count > 0 && $limit > 0) 
{ 
	$total_pages = ceil($count / $limit); 
} 
else 
{ 
	$total_pages = 0; 
} 
 
if ($page > $total_pages) $page = $total_pages;
 
$start = $limit * $page - $limit;
 
if($start <0) $start = 0; 
if(!$sidx) $sidx = 1;

$ops = array(
        'eq'=>'=', 
        'ne'=>'<>',
        'lt'=>'<', 
        'le'=>'<=',
        'gt'=>'>', 
        'ge'=>'>=',
        'bw'=>'LIKE',
        'bn'=>'NOT LIKE',
        'in'=>'LIKE', 
        'ni'=>'NOT LIKE', 
        'ew'=>'LIKE', 
        'en'=>'NOT LIKE', 
        'cn'=>'LIKE', 
        'nc'=>'NOT LIKE' 
    );

if(isset($_GET['searchString']) && isset($_GET['searchField']) && isset($_GET['searchOper']))
{
    $searchString = $_GET['searchString'];
    $searchField = $_GET['searchField'];
    $searchOper = $_GET['searchOper'];

    foreach ($ops as $key=>$value)
    {
        if ($searchOper==$key)
        {
            $ops = $value;
        }
    }

    if($searchOper == 'eq' ) $searchString = $searchString;
    if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
    if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
    if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

    $where = "$searchField $ops '$searchString'"; 

    $users = User::get_by_sql("SELECT * FROM ".T_USERS." WHERE ".$where." ORDER BY $sidx $sord LIMIT $start , $limit");
}
else
{
    $users = User::get_by_sql("SELECT * FROM ".T_USERS." ORDER BY $sidx $sord LIMIT $start , $limit");
}

header("Content-type: text/xml;charset=utf-8");
 
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";

foreach($users as $user) 
{
    $s .= "<row id='". $user->id."'>";
    $s .= "<cell></cell>";           
    $s .= "<cell>". $user->username."</cell>";
    $s .= "<cell>". $user->password."</cell>";
    $s .= "<cell>". $user->email."</cell>";
    $s .= "<cell>". $user->name."</cell>";
    $s .= "<cell>". $user->level."</cell>";
    $s .= "<cell>". $user->lives."</cell>";
    $s .= "<cell>". $user->bullets."</cell>";
    $s .= "<cell>". $user->coins."</cell>";
    $s .= "<cell>". $user->shields."</cell>";
    $s .= "<cell>". $user->kills."</cell>";
    $s .= "<cell>". $user->slowmos."</cell>";
    $s .= "<cell>". $user->points."</cell>";
    $s .= "<cell>". $user->top_score."</cell>";
    $s .= "<cell>". $user->date."</cell>";
    $s .= "<cell>". $user->volume."</cell>";
    $s .= "<cell>". $user->control."</cell>";
    $s .= "<cell>". $user->language."</cell>";
    $s .= "<cell>". $user->enabled."</cell>";
    $s .= "<cell>". $user->admin."</cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}

$s .= "</rows>"; 
 
echo $s;
?>