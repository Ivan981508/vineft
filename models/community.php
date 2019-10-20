<?php


class community
{
    public static function publicInfo($id = "default")
    {
        $db = db::getConnection();// Соединение с БД

        $sql = 'SELECT * FROM `public` WHERE id = :id';
        $result = $db->prepare($sql);// Используется подготовленный запрос
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
        $result->execute();// Выполнение коменды

        $publicInfo = array();
        $row = $result->fetch();

        if(!$row) return false;
        else {
            foreach ($row as $key => $value) {
            	$publicInfo[$key] = $value;
            }
            if($publicInfo['avatar'] == "") $publicInfo['avatar'] = "no_avatar.jpg";
            $publicInfo['users'] = dialog::load("public",$id);
            return $publicInfo;
        }
    }
    public static function deletePublic($public_id){
        $community = self::publicInfo($public_id);
        $user = user::userInfo();
        if($community['admin'] == $user['id'])
        {
            if($community['avatar'] != "no_avatar.jpg")
            {
                if(!unlink(ROOT."/template/image/public_photo/".$community['avatar'])) return false;
            }
            dialog::deleteAllDialog($public_id);
            
            $db = db::getConnection();
            $sql = "DELETE FROM `public` WHERE id=:public_id";
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':public_id', $public_id, PDO::PARAM_INT); 
            $result->execute();// Выполнение коменды
            return true;
        }
        else return false;
    }
    public static function createPublic($name,$avatar,$type,$students)
    {
        if(user::checkToken())
        {
            if($avatar == "none") $new_avatar = "";
            else $new_avatar = substr(md5(uniqid()."vneft-img-public"),5,8).".jpg";
            if($avatar != "none" && !rename("template/image/temporary/".$avatar, "template/image/public_photo/".$new_avatar)) $answer = ['status'=>"fail","Не удалось загрузить аватар"];
            else {
                $user = user::userInfo();
                $id_user = $user['id'];

                if($type == 2) $param = $user['specialty'];
                else $param = "";
                
                $db = db::getConnection();

                $result = $db->prepare("INSERT INTO `public`(`name`, `avatar`, `type`, `param`, `admin`) VALUES (:name,:avatar,:type,:param,:admin)");

                $result->bindParam(':name', $name);
                $result->bindParam(':avatar', $new_avatar);
                $result->bindParam(':type', $type);
                $result->bindParam(':param', $param);
                $result->bindParam(':admin', $id_user);
                $result->execute();

                $last_id = $db->lastInsertId();
                if($last_id == 0) $answer = ['status'=>"fail","type"=>"Не удалось создать сообщество"];
                else {
                    //array_push($students, $user['id']);//Добавляем создателя в массив участников
                    //$count_student = count($students);//Считаем количество участников
                    self::update($last_id,['number_user'],[1]);//Обновляем количество участников
                    //for($i=0;$i<$count_student;$i++) dialog::insertDialog($students[$i],$id_public,"public");//Добавляем студентов в участников группы

                    dialog::insertDialog($user['id'],$last_id,"public");
                    $dialog_id = dialog::isDialogLoad($last_id,"public",1);
                    
                    $send_invite = invite::inviteContact($last_id,$students);
                    if($send_invite['status'] == "success") $answer = ['status'=>"success","invite"=>"sending","data"=>$send_invite['data'],"dialog"=>$dialog_id];
                    else {
                        if($send_invite['status'] == "warning") $answer = ['status'=>"success","invite"=>"no_select"];
                        else $answer = ['status'=>"success","invite"=>"error"];
                    }
                }
            }
            return $answer;
        }
    }
    public static function actionPublic($public_id)
    {
        $user_id = user::userInfo()['id'];
        if(self::isPublic($public_id))//Отписаться
        {
            if(self::leaveUser($user_id,$public_id)) {
                $dialog_id = dialog::isDialogLoad($public_id,"public","public");
                $unread = message::checkRead($dialog_id);
                $answer = ['status'=>"success","type"=>"leave","dialog"=>$dialog_id,"unread"=>$unread];
            }
            else $answer = ['status'=>"fail"];
        }
        else { //Вступить
            $data = dialog::createDialog($user_id,$public_id,"public");
            if($data['status'] == "success") {
                invite::deleteInvite($public_id,$user_id);

                $dialog_load = dialog::load("id_dialog",$data['id'])[0];
                $answer = ['status'=>"success","type"=>"join","dialog"=>$dialog_load['dialog_id']];
                if($dialog_load != "none") {
                    message::deleteMessage($dialog_load['dialog_id']);
                    message::readMessage($dialog_load['dialog_id']);
                }
            }
            else $answer = ['status'=>"fail"];
        }
        return $answer;
    }
    public static function loadPublic()
    {
        return dialog::load("my_public");
    }
    public static function listPublicView($publics,$title="")
    {
        $html = "";
        switch ($title) {
            case 'group_1': $html.= '<p class="search_title">Ваши сообщества</p>'; break;
            case 'group_2': $html.= '<p class="search_title">Глобальный поиск</p>'; break;
            default: $html.= ''; break;
        }
        if($publics != "none")
        {
            for($i=0;$i<count($publics);$i++) 
            { 
                $public = self::publicInfo($publics[$i]);
                $html.= "<div class='public link' data-type='public' data-id='".$public['id']."'>
                    <div class='public_type' data-type='".$public['type']."'></div>
                    <div class='user_img size_40'>
                        <img src='template/image/public_photo/".$public['avatar']."'>
                    </div>
                    <div class='public_info'>
                        <strong>".$public['name']."</strong>
                        <p>".$public['number_user']." человек</p>
                    </div>
                </div>";
            }
            return $html;
        }
    }
    public static function isPublic($public,$user="default")
    {
        return dialog::isDialog($public,"public",$user);
    }
    public static function update($id,$key,$value)
    {
        $db = db::getConnection();// Соединение с БД
        $insert = "";
        for($i=0;$i<count($key);$i++) $insert.= "`".$key[$i]."`=:".$key[$i].",";
        $insert = substr($insert,0,-1);

        $sql = "UPDATE `public` SET ".$insert." WHERE id='$id'";
        $result = $db->prepare($sql);

        for($i=0;$i<count($key);$i++){
            $placeholder = ":".$key[$i];
            $result->bindParam($placeholder, $value[$i]);
        }
        $result->execute();

        if($result) return true;
        else return false;
    }
    public static function leaveUser($user_id,$public){
        if(dialog::actionDialog("leave",$user_id,$public,"public")) {
            $public = self::publicInfo($public);

            $number_user = $public['number_user']-1;
            self::update($public['id'],['number_user'],[$number_user]);
            return true;
        }
        else return false;
    }
    public static function updateInfo($public,$avatar,$name,$type){
        $user = user::userInfo();
        $publicInfo = community::publicInfo($public);

        if($publicInfo['admin'] == $user['id'])
        {
            if($avatar == "none") $new_avatar = $publicInfo['avatar'];
            else $new_avatar = substr(md5(uniqid()."vneft-img-public"),5,8).".jpg";

            $error = 0;
            if($avatar!= "none")
            {
                if($publicInfo['avatar'] != "no_avatar.jpg")
                {
                    if(!unlink("template/image/public_photo/".$publicInfo['avatar'])) $error++;
                    else 
                    {
                        if(!rename("template/image/temporary/".$avatar, "template/image/public_photo/".$new_avatar)) $error++;
                    }
                }
                else {
                    if(!rename("template/image/temporary/".$avatar, "template/image/public_photo/".$new_avatar)) $error++;
                }
            }
            if($error == 0)
            {
                if(self::update($public,["avatar","name","type"],[$new_avatar,$name,$type])) $answer = ['status'=>"success"];
                else $answer = ['status'=>"fail","type"=>"Ошибка обновления"];
            }
            else $answer = ['status'=>"fail","type"=>"Ошибка загрузки"];

        }
        else $answer = ['status'=>"fail","type"=>"Вы не администратор"];

        return $answer;
    }
}