<?php

class dialog
{
    public static function load($type="all",$id_public=0)
    {
        if(user::checkToken())
        {
        	$my_info = user::userInfo();
            $id = $my_info['id'];

            $db = db::getConnection();
            $request = "%".$id." %";

            if($type=="all") $sql = 'SELECT * FROM `dialogs` WHERE (client1 = :id OR client2 =:id) AND user_delete NOT LIKE :request ORDER BY date DESC';
            else if($type=="my_public") $sql = "SELECT * FROM `dialogs` WHERE client1 = :id AND type ='public'";
            else if($type == "public") {
                $id = $id_public;
                $sql = "SELECT * FROM `dialogs` WHERE client2 = :id AND type ='public'";
            }
            else if($type == "id_dialog")
            {
                $id = $id_public;
                $sql = "SELECT * FROM `dialogs` WHERE id=:id";
            }

            $result = $db->prepare($sql);

            if($type=="all") $result->bindParam(':request', $request);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $dialog = [];
            $count_dialog = 0;
            while ($row = $result->fetch()) {

                if($type == "public") $dialog[$count_dialog] = $row['client1'];
                else if($type == "my_public") $dialog[$count_dialog] = $row['client2'];
                else {
    	            foreach ($row as $key => $value) {
    	            	if($key == "client1" || $key == "client2")
    	            	{
                            $user_id = user::userInfo()['id'];
    	            		if($value != $user_id) $dialog[$count_dialog]["client"] = $value;
    	            	}
    	                else $dialog[$count_dialog][$key] = $value;
                        
    	            }
                    $dialog[$count_dialog]['unread'] = message::checkRead($dialog[$count_dialog]['dialog_id']);
                }

                $count_dialog++;
            }
            $answer = "none";
            if($count_dialog != 0) $answer = $dialog;
            return $answer;
        }
    }
    public static function listDialogView($dialogs)
    {
        $html = "";
        if($dialogs != "none")
        {
            for($i=0;$i<count($dialogs);$i++) 
            { 
            	$dialog = $dialogs[$i];
            	$avatar_patch = "";$avatar = "";
                $id = $dialog['client'];

                $text_message = message::lastMessage($dialog['dialog_id']);
            	if($dialog['type'] == "students")
            	{
            		$user = user::userInfo($id);
            		$avatar_patch = "students_photo/".$user['avatar'];
            		$name = $user['name'];
                    $type = "profile";
            	}
            	else {
            		$public = community::publicInfo($id);
            		$avatar_patch = "public_photo/".$public['avatar'];
            		$name = $public['name'];
                    $type = "public";
            	}
 
                $html.= "<div class='dialog' data-type='".$type."' data-id='".$id."' dialog_id='".$dialog['dialog_id']."'>
                    <div class='user_img size_40'>
                        <img src='template/image/".$avatar_patch."'>
                    </div>
                    <div class='dialog_info'>
                        <strong>".$name."</strong>
                        <p>".$text_message."</p>
                    </div>
                    <span class='m_counter' data-unread='".$dialog['unread']."'><i>".$dialog['unread']."</i></span>
                </div>";
            }
            return $html;
        }
    }
    public static function viewDialogOne($dialog_id,$unread)
    {
        $dialog = self::load("id_dialog",$dialog_id);
        $html = "";
        if($dialog != "none")
        {
            $dialog = $dialog[0];
            $avatar_patch = "";$avatar = "";
            $id = $dialog['client'];

            $text_message = message::lastMessage($dialog['id']);
            if($dialog['type'] == "students")
            {
                $user = user::userInfo($id);
                $avatar_patch = "students_photo/".$user['avatar'];
                $name = $user['name'];
                $type = "profile";
            }
            else {
                $public = community::publicInfo($id);
                $avatar_patch = "public_photo/".$public['avatar'];
                $name = $public['name'];
                $type = "public";
            }

            $html.= "<div class='dialog' data-type='".$type."' data-id='".$id."' dialog_id='".$dialog['dialog_id']."'>
                <div class='user_img size_40'>
                    <img src='template/image/".$avatar_patch."'>
                </div>
                <div class='dialog_info'>
                    <strong>".$name."</strong>
                    <p>".$text_message."</p>
                </div>
                <span class='m_counter' data-unread='".$dialog['unread']."'><i>".$dialog['unread']."</i></span>
            </div>";
            return $html;
        }
        else return false;
    }
    public static function isDialogLoad($client,$type,$param="auto"){
        $id_user = user::userInfo()['id'];
        $db = db::getConnection();
        if($param == "auto") $sql = "SELECT * FROM `dialogs` WHERE ((client1=:client_id AND client2=:user_id) OR (client1=:user_id AND client2=:client_id)) AND type=:type";
        else $sql = "SELECT * FROM `dialogs` WHERE client2=:client_id AND type=:type";

        $result = $db->prepare($sql);
        $result->bindParam(':client_id', $client, PDO::PARAM_INT);
        if($param == "auto") $result->bindParam(':user_id', $id_user, PDO::PARAM_INT);
        $result->bindParam(':type', $type, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
                
        $row = $result->fetch();
        if(isset($row['dialog_id'])) return $row['dialog_id'];
        else return "none";
    }
    public static function isDialog($dialog,$param,$user="default"){
        if($user == "default") $id_user = user::userInfo()['id'];
        else $id_user = $user;

    	$db = db::getConnection();
    	$sql = "SELECT COUNT(*) FROM `dialogs` WHERE ((client1=:client_id AND client2=:user_id) OR (client1=:user_id AND client2=:client_id)) AND type=:param";

    	$result = $db->prepare($sql);
        $result->bindParam(':client_id', $dialog, PDO::PARAM_INT);
        $result->bindParam(':user_id', $id_user, PDO::PARAM_INT);
        $result->bindParam(':param', $param, PDO::PARAM_INT);
        $result->execute();
	            
	    $result->execute();// Выполнение коменды
	    $num_rows = $result->fetchColumn();
        if($num_rows >0) return true;
        else return false;
    }

    public static function createDialog($id,$dialog,$type)
    {
        if(user::checkToken())
        {
            if($type=="public")
            {
                if(self::isDialog($dialog,$type,$id)) $answer = ['status'=>"fail","type"=>"Вы состоите в данном сообществе"];
                else {
                    $public = community::publicInfo($dialog);
                    $user = user::userInfo();

                    if($public['type'] == "1" || $public['type'] == "2" && $user['specialty'] == $public['param'] || $public['type'] == "3" && invite::isInvite($public['id']))
                    {
                        $existing_dialog_id = self::isDialogLoad($dialog,"public",1);
                        if($existing_dialog_id == "none") $existing_dialog_id = "auto";

                        $answer = self::insertDialog($id,$dialog,$type,$existing_dialog_id);
                        $number_user = $public['number_user']+1;
                        community::update($public['id'],['number_user'],[$number_user]);
                    }
                    else $answer = ['status'=>"fail","type"=>"Вы не можете вступить в данное сообщество"];
                }
            }
            else if($type == "students")
            {
                if(self::isDialog($dialog,$type)) {
                    $dialog_id = self::isDialogLoad($dialog,"students");

                    $load_dialog = self::load("id_dialog",$dialog_id)[0];
                    $user = user::userInfo();

                    if(strpos($load_dialog['user_delete'], $user['id']." ") === false) $answer = ['status'=>"fail","type"=>"Диалог уже существует"];
                    else {
                        $new_user_delete = preg_replace("/(".$user['id']." )/", '', $load_dialog['user_delete']);
                        self::update($dialog_id,['user_delete'],[$new_user_delete]);
                        $answer = ["status"=>"success","id"=>$dialog_id,"client"=>$dialog];
                    }
                }
                else $answer = self::insertDialog($id,$dialog,$type);
            }
            else $answer = ['status'=>"fail","type"=>"Внутренняя ошибка"];

            return $answer;
        }
    }
    public static function insertDialog($id,$dialog,$type,$param="auto"){
        $date = date('Y-m-d H:i:s');
        $user = user::userInfo();

        $db = db::getConnection();

        if($type == "students")
        {
            $user_delete = $dialog." ";
            $sql = "INSERT INTO `dialogs`(`client1`, `client2`, `type`, `user_delete`, `date`) VALUES (:user,:dialog,:type,:user_delete,:date_create)";
        }
        else $sql = "INSERT INTO `dialogs`(`client1`, `client2`, `type`, `date`) VALUES (:user,:dialog,:type,:date_create)";
        $result = $db->prepare($sql);// Используется подготовленный запрос

        if($type == "students") $result->bindParam(':user_delete', $user_delete);
        $result->bindParam(':user', $id);
        $result->bindParam(':dialog', $dialog);
        $result->bindParam(':type', $type);
        $result->bindParam(':date_create', $date);
        $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                        
        $result->execute();// Выполнение коменды
        $id = $db->lastInsertId();

        if($type == "public")
        {

            if($param != "auto") self::update($id,['dialog_id'],[$param]);
            else self::update($id,['dialog_id'],[$id]);
        }
        else self::update($id,['dialog_id'],[$id]);
        return ["status"=>"success","id"=>$id,"client"=>$dialog];
    }

    public static function update($id,$key,$value)
    {
        if(user::checkToken())
        {
            $id = intval($id);
            $db = db::getConnection();// Соединение с БД
            $insert = "";
            for($i=0;$i<count($key);$i++) $insert.= "`".$key[$i]."`=:".$key[$i].",";
            $insert = substr($insert,0,-1);

            $sql = "UPDATE `dialogs` SET ".$insert." WHERE id='$id'";
            $result = $db->prepare($sql);

            for($i=0;$i<count($key);$i++){
                $placeholder = ":".$key[$i];
                $result->bindParam($placeholder, $value[$i]);
            }
            $result->execute();

            if($result) return true;
            else return false;
        }
        else return false;
    }
    public static function deleteAllDialog($public){
        $community = community::publicInfo($public);
        $user = user::userInfo();
        if($community['admin'] == $user['id']) {
            $db = db::getConnection();
            $sql = "DELETE FROM `dialogs` WHERE client2=:public AND type='public'";
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':public', $public, PDO::PARAM_INT);
                                
            $result->execute();// Выполнение коменды
            return true;
        }
        else return false;
    }
    public static function deleteDialog($dialog_id){
        $dialog_id = intval($dialog_id);
        $db = db::getConnection();

        $user_id = user::userInfo()['id'];
        $request = "%".$user_id."%";
        
        $dialog = self::load("id_dialog",$dialog_id);
        $error = false;

        if($dialog != "none" && $dialog[0]['type'] == "students")
        {
            $dialog = $dialog[0];
            $user_delete = $dialog['user_delete'];
            message::deleteMessage($dialog_id);

            if(!functions::is_empty($user_delete) && strpos($user_delete, $user_id." ") === false) $sql = "DELETE FROM `dialogs` WHERE dialog_id='$dialog_id'";
            else if(strpos($user_delete, $user_id." ") === false){
                $sql = "UPDATE `dialogs` SET `user_delete`= CONCAT(user_delete,'$user_id ') WHERE dialog_id='$dialog_id'";
            }
            else $error=true;

            if(!$error)
            {
                $result = $db->prepare($sql);
                $result->execute();// Выполнение коменды
                return true;
            }
            else return false;
        }
        else return false;
    }
    public static function actionDialog($action,$user,$dialog,$param)
    {
        if($action == "leave")
        {
            if(self::isDialog($dialog,$param,$user))
            {   
                $db = db::getConnection();
                $sql = "DELETE FROM `dialogs` WHERE ((client1=:client_id AND client2=:user_id) OR (client1=:user_id AND client2=:client_id)) AND type=:param";
                $result = $db->prepare($sql);// Используется подготовленный запрос

                $result->bindParam(':client_id', $dialog);
                $result->bindParam(':user_id', $user);
                $result->bindParam(':param', $param);
                                
                $result->execute();// Выполнение коменды

                if(!self::isDialog($dialog,$param,$user)) return true;
                else return false;
            }
            else return false;
        }
        else return false;
    }
    public static function updateDate($dialog_id){
        $date = date('Y-m-d H:i:s');
        $db = db::getConnection();
        $sql = "UPDATE `dialogs` SET `date`=:date_time WHERE dialog_id=:dialog_id";
        $result = $db->prepare($sql);// Используется подготовленный запрос

        $result->bindParam(':date_time', $date);
        $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
                                
        $result->execute();// Выполнение коменды

        if($result->errorInfo()[0] == "00000") return true;
        else return false;
    }
}