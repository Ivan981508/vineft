<?php


class user_block
{
    public static function load($id = "my")
    {
    	if($id == "my") $userInfo = user::userInfo();
    	else $userInfo = user::userInfo($id);

 		$friends = user::loadFriends();
    	require_once(ROOT . '/views/layouts/user_block.php');
    }
}