<?php


class user
{
    public static function autorization()
    {
        if (!isset($_GET['code'])) return ('error');

        include ROOT.'/config/vk_params.php';
        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token?client_id='.ID.'&redirect_uri='.URL.'&client_secret='.SECRET.'&code='.$_GET['code']), true);

        if (!$token) return ('error');


        $data = json_decode(file_get_contents('https://api.vk.com/method/users.get?user_id='.$token['user_id'].'&v=5.0&access_token='.$token['access_token'].'&fields=id,first_name,last_name,photo_big'), true);


        if (!$data) return ($data['error']);
        $data = $data['response'][0];

        if(self::checkUserDb($data['id'])) {
            $token = self::generateToken($data['id']);
            $_SESSION['vneft_token'] = [$data['id'],$token];
            return ("success");
        }
        else {
            $_SESSION['vneft_token'] = [$data['id'],"create_account"];
            return("unregister");
        }

    }
    public static function createAccount($name,$avatar,$surname,$birthday,$city,$stud_ticket,$specialty,$course,$pol,$about_me)
    {
        if(isset($_SESSION['vneft_token']) && $_SESSION['vneft_token'][1] == 'create_account')
        {
            $vk = $_SESSION['vneft_token'][0];

            if(!self::checkUserDb($vk)) {
                $new_avatar = substr(md5(uniqid()."vneft-img-user"),5,8).".jpg";
                if (!rename("template/image/temporary/".$avatar, "template/image/students_photo/".$new_avatar)) return "fail";
                else {
                    $name = $name." ".$surname;
                    $date_reg = date("Y-m-d H:i:s");
                    $db = db::getConnection();

                    $result = $db->prepare("INSERT INTO `students`(`vk`, `name`, `avatar`, `birthday`, `city`, `stud_ticket`, `specialty`, `course`, `pol`, `about_me`, `date`, `settings`) VALUES (:vk,:name,:avatar,:birthday,:city,:stud_ticket,:specialty,:course,:pol,:about_me,'$date_reg','1,1,0')");
                    $result->bindParam(':vk', $vk);
                    $result->bindParam(':name', $name);
                    $result->bindParam(':avatar', $new_avatar);
                    $result->bindParam(':birthday', $birthday);
                    $result->bindParam(':city', $city);
                    $result->bindParam(':stud_ticket', $stud_ticket);
                    $result->bindParam(':specialty', $specialty);
                    $result->bindParam(':course', $course);
                    $result->bindParam(':pol', $pol);
                    $result->bindParam(':about_me', $about_me);
                    $result->execute();

                    $id_user = $db->lastInsertId();

                    $db->query("INSERT INTO `contacts`(`id`) VALUES ('$id_user')");

                    if($id_user == 0) return "fail2";
                    else {
                        $token = self::generateToken($vk);
                        $_SESSION['vneft_token'] = [$vk,$token];
                        community::actionPublic($specialty);
                        return "success";
                    }
                }
            }
            return "fail"; 
        }
        return "fail";
    }
    public static function userInfo($id = "default")
    {
        $solution = false;
        if($id == "default")
        {
            if(!self::checkToken()) $solution = false;
            else {
                $vk = $_SESSION['vneft_token'][0];
                $param = $vk;
                $sql = 'SELECT * FROM `students` WHERE vk = :value';
                $solution = true;
            }
        }
        else {
            $param = $id;
            $sql = 'SELECT * FROM `students` WHERE id = :value';
            $solution = true;
        }

        if(!$solution) return false;
        else {
            $db = db::getConnection();// Соединение с БД

            $result = $db->prepare($sql);// Используется подготовленный запрос
            $result->bindParam(':value', $param, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $userInfo = array();
            $row = $result->fetch();

            if(!$row) return false;
            else {
                $count_social = 0;$empty_social = 0;
                foreach ($row as $key => $value) {
                    if($key == "vk" || $key == "instagram" || $key == "facebook" || $key == "twitter") {
                        if($value == "") $empty_social++;
                        $userInfo['social'][$count_social] = ["value"=>$value,"network"=>$key];
                        $count_social++;
                    }
                    $userInfo[$key] = $value;
                }
                $date = strtotime(date('Y-m-d H:i:s'));
                if(strtotime($userInfo['last_activity'])+180 > $date) $userInfo['status'] = "online";
                else $userInfo['status'] = "offline";

                $userInfo['count_social'] = $count_social-$empty_social;
                $day = floor((time()-strtotime ($userInfo['date']))/(60*60*24));
                $userInfo['date'] = functions::inflection($day);
                $userInfo['birthday'] = functions::f_date($userInfo['birthday']);

                $settings = explode(",",$userInfo['settings']);
                $userInfo['settings'] = [];
                for($i=0;$i<count($settings);$i++)
                {
                    if($settings[$i] == 0) $userInfo['settings'][$i] = "";
                    else $userInfo['settings'][$i] = "checked";
                }
                return $userInfo;
            }
        }
    }
    public static function loadFriends(){
        if(self::checkToken())
        {
            $userInfo = self::userInfo();
            $id = $userInfo['id'];

            $db = db::getConnection();
            $sql = 'SELECT * FROM `contacts` WHERE id = :id';
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $data = "none";
            while ($row = $result->fetch()) {
                $data = $row['array_friends'];
            }
            if($data == "none" || $data=="") $contacts = "none";
            else $contacts = explode(",",$data);
            return $contacts;
        }
    }
    public static function actionContact($action,$id){
        $new_contacts = [];
        $clear_contact = false;
        if($action == "remove")
        {
            if(self::checkContact($id))
            {
                $contacts = self::loadFriends();
                for($i=0;$i<count($contacts);$i++)
                {
                    if($contacts[$i] == $id) continue;
                    else array_push($new_contacts, $contacts[$i]);
                }
                if(!$new_contacts) $clear_contact = true;
            }
        }
        else if($action == "add"){
            if(!self::checkContact($id))
            {
                $contacts = self::loadFriends();
                if($contacts == "none") array_push($new_contacts, $id);
                else {
                    array_push($contacts, $id);
                    $new_contacts = $contacts;
                }
            }
        }
        if($new_contacts || $clear_contact)
        {
            $data = "";
            if(!$clear_contact)
            {
                for($i=0;$i<count($new_contacts);$i++) $data.= $new_contacts[$i].",";//Собираем строку
                $data = substr($data,0,-1);//Удаляем последний символ 
            }

            $db = db::getConnection();
            $sql = 'UPDATE `contacts` SET `array_friends`=:data WHERE id=:id';
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':id', self::userInfo()['id'], PDO::PARAM_INT);
            $result->bindParam(':data', $data, PDO::PARAM_STR);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
                
            $result->execute();// Выполнение коменды        
            return true;
        }
        else return false;
    }
    public static function checkContact($id){
        $contacts = self::loadFriends();
        if($contacts != "fail" && $contacts != "none")
        {
            $search = 0;
            for($i=0;$i<count($contacts);$i++)
            {
                if($contacts[$i] == $id)
                {
                    $search++;
                    break;
                }
            }
            if($search == 0) return false;
            else return true;
        }
        else return false;
    }





    //Проверочные функций
    public static function checkUserDb($vk)
    {
        $db = db::getConnection();// Соединение с БД

        $result = $db->prepare('SELECT COUNT(*) FROM `students` WHERE vk=:vk');
        $result->bindParam(':vk', $vk, PDO::PARAM_INT);
        $result->execute();

        $num_rows = $result->fetchColumn();
        if($num_rows == 0) return false;
        else if($num_rows >= 1) return true;
    }

    public static function generateToken($vk)
    {
        $db = db::getConnection();// Соединение с БД

        $result = $db->prepare('SELECT `id` FROM `students` WHERE vk=:vk');
        $result->bindParam(':vk', $vk, PDO::PARAM_INT);
        $result->execute();
        $data = $result->fetch();

        $token = md5($vk."vneft-dp".$data['id']);
        return $token;
    }

    public static function checkToken()
    {
        if(!isset($_SESSION['vneft_token'])) return false;
        else {
            $vk = $_SESSION['vneft_token'][0];
            $hash = $_SESSION['vneft_token'][1];

            $db = db::getConnection();// Соединение с БД

            $result = $db->prepare('SELECT `id` FROM `students` WHERE vk=:vk');
            $result->bindParam(':vk', $vk, PDO::PARAM_INT);
            $result->execute();
            $data = $result->fetch();

            $token = md5($vk."vneft-dp".$data['id']);
            if($token != $hash) return false;
            else return true;
        }
    }
    public static function update($key,$value)
    {
        if(self::checkToken())
        {
            $user = self::userInfo();
            $id = $user['id'];
            $db = db::getConnection();// Соединение с БД
            $insert = "";
            for($i=0;$i<count($key);$i++) $insert.= "`".$key[$i]."`=:".$key[$i].",";
            $insert = substr($insert,0,-1);

            $sql = "UPDATE `students` SET ".$insert." WHERE id='$id'";
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

    public static function delete(){
        if(self::checkToken())
        {
            $user = self::userInfo();
            $id = $user['id'];
            $vk = $user['vk'];
            $avatar = $user['avatar'];

            $db = db::getConnection();// Соединение с БД
            $db->query("DELETE FROM `students` WHERE id='$id'");

            if(!self::checkUserDb($vk))
            {
                unset($_SESSION['vneft_token']);
                unlink("template/image/students_photo/".$avatar);
                return true;
            }
            else return false;
        }
    }
    public static function updateSetting($id_param){
        if(self::checkToken())
        {
            $new_settings = "";

            $settings = self::userInfo()['settings'];

            if($settings[$id_param] == "") $settings[$id_param] = "checked";
            else $settings[$id_param] = "";

            for($i=0;$i<count($settings);$i++)
            {
                if($settings[$i] == "") $new_settings.="0,";
                else $new_settings.="1,";
            }
            $new_settings = substr($new_settings,0,-1);//Удаляем последний символ 
            self::update(['settings'],[$new_settings]);
        }
    }
}
