<?php

class message
{
    public static function load($dialog_id,$set="all")
    {
    	if(user::checkToken())
    	{
            $user = user::userInfo();
            $db = db::getConnection();
            $request = "%".$user['id']." %";
            $from = 0;
            $to = 0;
            if($set == "all") {
                $sql = 'SELECT * FROM `messages` WHERE dialog_id = :dialog_id';
                $param = "none";
            }
            else if($set == "one"){
                $sql = 'SELECT * FROM `messages` WHERE id = :dialog_id';
                $param = "none";
            }
            else
            {
                if($set != "initial") $from = $set;
        		$to = 10;
        		$count = self::countMessage($dialog_id);

        		if($count-$from < $to) {
        			$to = $count-$from;
        			$param = "none";
        		}
        		else $param = $from+$to;
        		$sql = 'SELECT * FROM (SELECT * FROM `messages` WHERE dialog_id = :dialog_id AND user_delete NOT LIKE :request ORDER BY id DESC LIMIT :from,:to) t ORDER BY id ASC';
            }
    		$result = $db->prepare($sql);

            if($set == "all" || $set == "one") $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
            else {
                $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
                $result->bindParam(':request', $request, PDO::PARAM_INT);
                $result->bindParam(':from', $from, PDO::PARAM_INT);
                $result->bindParam(':to', $to, PDO::PARAM_INT);
            }
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $message = [];
            $count_message = 0;
            while ($row = $result->fetch()) {
            	foreach ($row as $key => $value) {
                    if($set == "one") $message[$key] = $value;
    	           	else $message[$count_message][$key] = $value;
    	        }

                if($set == "one") {
                    $message['date'] = functions::f_date_message($message['date']);
                    $str = "/(".$user['id']." )/";
                    if(preg_match($str, $message['user_read']))
                    {
                        $message['user_read'] = preg_replace($str, '', $message['user_read']);
                        if($message['user_read'] == "") $message['user_read'] = "unread";
                        else $message['user_read'] = "read";
                    }
                    else {
                        if($message['user_read'] == "") $message['user_read'] = "unread";
                        else $message['user_read'] = "read";
                    }
                }
    	        else {
                    $message[$count_message]['date'] = functions::f_date_message($message[$count_message]['date']);

                    $str = "/(".$user['id']." )/";
                    if(preg_match($str, $message[$count_message]['user_read']))
                    {
                        $message[$count_message]['user_read'] = preg_replace($str, '', $message[$count_message]['user_read']);
                        if($message[$count_message]['user_read'] == "") $message[$count_message]['user_read'] = "unread";
                        else $message[$count_message]['user_read'] = "read";
                    }
                    else {
                        if($message[$count_message]['user_read'] == "") $message[$count_message]['user_read'] = "unread";
                        else $message[$count_message]['user_read'] = "read";
                    }
                }
    	        $count_message++;
            }
            if($count_message != 0) $message = $message;
            else $message = "none";
            $answer = ['message'=>$message,"param"=>$param];
            return $answer;
    	}
    }
    public static function deleteMessage($id){
        $id = intval($id);
        $db = db::getConnection();

        $user_id = user::userInfo()['id'];
        $request = "%".$user_id."%";

        $message = self::load($id)['message'];
        if($message != "none")
        {
            for($i=0;$i<count($message);$i++){
                $dialog_id = $message[$i]['dialog_id'];
                $user_delete = $message[$i]['user_delete'];
                $increment = $message[$i]['id'];

                $dialog = dialog::load("id_dialog",$dialog_id);
                $error = false;
                if($dialog != "none") {

                    $dialog = $dialog[0];
                    $type_dialog = $dialog['type'];

                    if($type_dialog == "students")
                    {
                        if(!functions::is_empty($user_delete) && strpos($user_delete, $user_id." ") === false) $sql = "DELETE FROM `messages` WHERE id='$increment'";
                        else if(strpos($user_delete, $user_id." ") === false){
                            $sql = "UPDATE `messages` SET `user_delete`= CONCAT(user_delete,'$user_id ') WHERE id='$increment'";
                        }
                        else $error=true;
                    }
                    else if($type_dialog == "public"){
                        $id_public = $dialog['client'];
                        $public = community::publicInfo($id_public);
                        $number_user = $public['number_user'];

                        if(!functions::is_empty($user_delete) && strpos($user_delete, $user_id." ") === false)
                        {
                            $count_user_del = count(explode(" ",$user_delete));
                            if($count_user_del >= $number_user) $sql = "DELETE FROM `messages` WHERE id='$increment'";
                            else $sql = "UPDATE `messages` SET `user_delete`= CONCAT(user_delete,'$user_id ') WHERE id='$increment'";
                        }
                        else if(strpos($user_delete, $user_id." ") === false){
                            if($number_user <= 1) $sql = "DELETE FROM `messages` WHERE id='$increment'";
                            else $sql = "UPDATE `messages` SET `user_delete`= CONCAT(user_delete,'$user_id ') WHERE id='$increment'";
                        }
                        else $error=true;
                    }
                    else $error=true;
                    if(!$error)
                    {
                        $result = $db->prepare($sql);
                        $result->execute();// Выполнение коменды
                    }
                }
            }
            return true;
        }
        else return false;
    }
    public static function lastMessage($dialog_id){
        if(user::checkToken())
        {
            $user = user::userInfo();
            $request = "%".$user['id']." %";

            $db = db::getConnection();
            $sql = 'SELECT * FROM (SELECT * FROM `messages` WHERE dialog_id = :dialog_id AND user_delete NOT LIKE :request ORDER BY id DESC LIMIT 1) t ORDER BY date ASC';
            $result = $db->prepare($sql);

            $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
            $result->bindParam(':request', $request, PDO::PARAM_STR);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $row = $result->fetch();

            $user = user::userInfo();
            $text_message = "";
            if(!$row) $text_message = "Диалог пуст";
            else {
                if($row['type'] == "sticker") $row['message'] = "Стикер";
                if($row['user'] == $user['id']) $text_message = "<span>Вы:</span> ".$row['message'];
                else $text_message = $row['message'];
            }
            return $text_message;
        }
    }
    public static function countMessage($dialog_id){
    	$db = db::getConnection();
        $user = user::userInfo();
        $request = "%".$user['id']." %";
    	$sql = "SELECT COUNT(*) FROM `messages` WHERE dialog_id = :dialog_id AND user_delete NOT LIKE :request";

    	$result = $db->prepare($sql);
        $result->bindParam(':request', $request, PDO::PARAM_INT);
        $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
        $result->execute();
	            
	    $result->execute();// Выполнение коменды
	    $num_rows = $result->fetchColumn();
        return $num_rows;
    }
    public static function viewMessage($message){
    	$html = '<div class="loading_message">
            <div class="loading"><div class="load_sw"></div></div>
        </div>';
    	if($message != "none")
    	{
	    	for($i=0;$i<count($message);$i++)
	    	{
	    		$my_profil = user::userInfo();
	    		$user = user::userInfo($message[$i]['user']);
	    		$user_name = explode(" ",$user['name'])[0];

	    		$my_message = "";
	    		if($message[$i]["user"] == $my_profil['id']) $my_message = "my_msg";

                $no_read = "";
                if($my_message == "my_msg"){
                    if($message[$i]["user_read"] == "unread") $no_read = "no_read";
                }

                $text = "<p class='text_msg'>".nl2br($message[$i]["message"])."</p>";
                if($message[$i]["type"] == "sticker") $text = '<img src="template/image/stickers/128/sticker_'.$message[$i]["message"].'.png">';
	    		$html.='<div class="msg '.$my_message.' '.$no_read.'">
	                <div class="contact_msg">
	                    <div class="user_img size_40">
	                        <img src="template/image/students_photo/'.$user["avatar"].'">
	                    </div>
	                    <a class="name_msg">'.$user_name.'</a>
	                </div>
	                <div class="data_msg">
	                    <p class="date_msg">'.$message[$i]["date"].'</p>
	                    '.$text.'
	                </div>
	            </div>';
	    	}
	    	return $html;
    	}
    }
    public static function saveMessage($dialog_id,$user,$message,$type){
        if(isset($dialog_id) && isset($user) && isset($message))
        {
            $user_read = $user." ";
            $date = date('Y-m-d H:i:s');
            $db = db::getConnection();
            $sql = 'INSERT INTO `messages`(`dialog_id`, `user`, `message`, `type`, `user_read`, `date`) VALUES (:dialog_id,:user,:message,:type,:user_read,:date_message)';
            $result = $db->prepare($sql);

            $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
            $result->bindParam(':user', $user, PDO::PARAM_INT);
            $result->bindParam(':message', $message, PDO::PARAM_STR);
            $result->bindParam(':type', $type);
            $result->bindParam(':user_read', $user_read);
            $result->bindParam(':date_message', $date);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                
            $result->execute();// Выполнение коменды
            $id_message = $db->lastInsertId();

            dialog::updateDate($dialog_id);
            if($id_message == 0) return false;
            else return $id_message;
        }
        else return false;
    }
    public static function viewOneMessage($id){
        $message = self::load($id,"one")['message'];
        $html = "";

        if($message != "none")
        {
            $my_profil = user::userInfo();
            $user = user::userInfo($message['user']);
            $user_name = explode(" ",$user['name'])[0];

            $my_message = "";
            if($message["user"] == $my_profil['id']) $my_message = "my_msg";

            $no_read = "";
            if($my_message == "my_msg"){
                if($message["user_read"] == "unread") $no_read = "no_read";
            }

            $text = "<p class='text_msg'>".nl2br($message["message"])."</p>";
            if($message["type"] == "sticker") $text = '<img src="template/image/stickers/128/sticker_'.$message["message"].'.png">';
            $html.='<div class="msg '.$my_message.' '.$no_read.'">
                <div class="contact_msg">
                    <div class="user_img size_40">
                        <img src="template/image/students_photo/'.$user["avatar"].'">
                    </div>
                    <a class="name_msg">'.$user_name.'</a>
                </div>
                <div class="data_msg">
                    <p class="date_msg">'.$message["date"].'</p>
                    '.$text.'
                </div>
            </div>';
            return $html;
        }
        else return "none";
    }
    public static function checkRead($dialog_id){
        $user = user::userInfo();
        $request = "%".$user['id']." %";

        $db = db::getConnection();
        $sql = 'SELECT * FROM `messages` WHERE dialog_id=:dialog_id AND user_read NOT LIKE :request AND user_delete NOT LIKE :request';
        $result = $db->prepare($sql);

        $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
        $result->bindParam(':request', $request, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                
        $result->execute();// Выполнение коменды

        $count = 0;
        while ($row = $result->fetch()) {
            $count++;
        }
        return $count;
    }
    public static function readMessage($dialog_id){
        $user = user::userInfo();
        $request = "%".$user['id']." %";

        $user_read = $user['id']." ";
        $db = db::getConnection();
        $sql = "UPDATE `messages` SET `user_read`=concat(user_read, :user_read) WHERE `id` IN (SELECT `id` FROM (SELECT `id` FROM `messages` WHERE dialog_id=:dialog_id AND user_read NOT LIKE :request) AS t2)";
        $result = $db->prepare($sql);

        $result->bindParam(':dialog_id', $dialog_id, PDO::PARAM_INT);
        $result->bindParam(':user_read', $user_read, PDO::PARAM_INT);
        $result->bindParam(':request', $request, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                
        $result->execute();// Выполнение коменды

        if($result->errorInfo()[0] == "00000") return true;
        else return false;
    }
}