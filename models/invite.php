<?php


class invite
{
    public static function load($type="all",$notice_id = 0)
    {
        if(user::checkToken())
        {
           	$user = user::userInfo();

           	$db = db::getConnection();
           	if($type == "all") {
                $id = $user['id'];
                $sql = 'SELECT * FROM `invite` WHERE invited=:id';
            }
            else if($type == "one") {
                $id = $notice_id;
                $sql = 'SELECT * FROM `invite` WHERE id=:id';
            }
           	$result = $db->prepare($sql);
           	$result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            $result->execute();// Выполнение коменды

            $invite = [];
            $count_invite = 0;
            while ($row = $result->fetch()) {

                foreach ($row as $key => $value) {
    	            $invite[$count_invite][$key] = $value;
    	        }

    	        $invite[$count_invite]['user_name'] = user::userInfo($invite[$count_invite]["user"])['name'];
                $count_invite++;
            }

            $answer = "none";
            if($count_invite != 0) $answer = $invite;
            return $answer;
        }
    }

    public static function countInvite(){
        if(user::checkToken())
        {
            $user = user::userInfo();
            $id = $user['id'];

            $db = db::getConnection();
            $sql = 'SELECT COUNT(*) FROM `invite` WHERE invited=:id';
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            $result->execute();// Выполнение коменды

            $num_rows = $result->fetchColumn();
            return $num_rows;
        }
    }
    public static function listInviteView($invite)
    {
    	if($invite != "none")
    	{
    		$count_invite = count($invite);
    		$html = '<p class="invite_title">Приглашения <span>'.$count_invite.'</span></p>';

    		for($i=0;$i<$count_invite;$i++) 
    		{
    			$public = community::publicInfo($invite[$i]['public']);
    			$html.= "<div class='invite_public' data-public='".$public['id']."'>
    			<div class='user_img size_40 link' data-type='public' data-id='".$public['id']."'>
    			<img src='template/image/public_photo/".$public['avatar']."'>
    			</div>
    			<div class='public_info'>
    			<strong class='link' data-type='public' data-id='".$public['id']."'>".$public['name']."</strong>
    			<p><span class='link' data-type='profile' data-id='".$invite[$i]['user']."'>".$invite[$i]['user_name']."</span> пригласил вас в сообщество</p>

    			<div class='action_invite'>
    			<input type='button' class='join' value='Вступить'>
    			<a href='#'>отклонить</a>
    			</div>
    			</div>


    			</div>";
    		}
    		return $html;
    	}
    }
    public static function viewInviteOne($invite)
    {
        $invite = self::load("one",$invite);
        if($invite != "none")
        {
            $invite = $invite[0];
            $count_invite = count($invite);
            $html = '';

            $public = community::publicInfo($invite['public']);
            $html.= "<div class='invite_public' data-public='".$public['id']."'>
                <div class='user_img size_40 link' data-type='public' data-id='".$public['id']."'>
                <img src='template/image/public_photo/".$public['avatar']."'>
                </div>
                <div class='public_info'>
                <strong class='link' data-type='public' data-id='".$public['id']."'>".$public['name']."</strong>
                <p><span class='link' data-type='profile' data-id='".$invite['user']."'>".$invite['user_name']."</span> пригласил вас в сообщество</p>

                <div class='action_invite'>
                <input type='button' class='join' value='Вступить'>
                <a href='#'>отклонить</a>
                </div>
                </div>
            </div>";
            return $html;
        }
        else return "none";
    }
    public static function isInvite($public_id,$user="default"){
        if($user == "default") $id_user = user::userInfo()['id'];
        else $id_user = $user;

        $db = db::getConnection();
        $sql = "SELECT COUNT(*) FROM `invite` WHERE invited = :id_user AND public = :public_id";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':public_id', $public_id, PDO::PARAM_INT);
        $result->execute();
                
        $num_rows = $result->fetchColumn();
        if($num_rows >0) return true;
        else return false;
    }
    public static function createInvite($invited,$public){
        if(!self::isInvite($public,$invited) && !community::isPublic($public,$invited))
        {
            $community = community::publicInfo($public);
            $user = user::userInfo();
            $invited_info = user::userInfo($invited);
            if($community['type'] == "1" || $community['type'] == "3" || $community['type'] == "2" && $invited_info['specialty'] == $community['param'] && $invited_info['settings'][0] == "checked")  
            {
                $user_id = $user['id'];
                $db = db::getConnection();
                $sql = "INSERT INTO `invite`(`invited`, `user`, `public`) VALUES (:invited,:user,:public)";
                $result = $db->prepare($sql);// Используется подготовленный запрос
                $result->bindParam(':invited', $invited, PDO::PARAM_INT);
                $result->bindParam(':user', $user_id, PDO::PARAM_INT);
                $result->bindParam(':public', $public, PDO::PARAM_STR);
                $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                                
                $result->execute();// Выполнение коменды

                $id_invite = $db->lastInsertId();
                $answer = $id_invite;
            }
            else $answer = false;
        }
        else $answer = false;
        return $answer;
    }
    public static function deleteInvite($public_id,$user="default"){
        if($user == "default") $user_id = user::userInfo()['id'];
        else $user_id = $user;
        
        if(self::isInvite($public_id,$user_id))
        {
            $db = db::getConnection();
            $sql = "DELETE FROM `invite` WHERE invited = :invited AND public = :public";
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':invited', $user_id, PDO::PARAM_INT);
            $result->bindParam(':public', $public_id, PDO::PARAM_INT);
                                
            $result->execute();// Выполнение коменды
            if(self::isInvite($public_id)) $answer = "false";
            else $answer = true;
        }
        else $answer = false;
        return $answer;
    }

    public static function inviteContact($public_id,$contact)
    {
        if($contact)
        {
            if(community::isPublic($public_id)){
                $data = [];
                for($i=0;$i<count($contact);$i++) {
                    $data[$i]['user'] = $contact[$i];
                    $data[$i]['invite'] = self::createInvite($contact[$i],$public_id);
                }
                $answer = ["status"=>"success","data"=>$data];
            }
            else $answer = ["status"=>"fail"];
        }
        else $answer = ['status'=>"warning","type"=>"no_select"];
        return $answer;
    }
}