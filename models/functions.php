<?php


class functions
{
    public static function numberof($numberof, $suffix)
    {
        // не будем склонять отрицательные числа
        $numberof = abs($numberof);
        $keys = array(2, 0, 1, 1, 1, 2);
        $mod = $numberof % 100;
        $suffix_key = $mod > 4 && $mod < 20 ? 2 : $keys[min($mod%10, 5)];
        
        return $numberof." ".$suffix[$suffix_key];
    }
    public static function inflection($number){
		return self::numberof($number, array('день', 'дня', 'дней'));
    }
    public static function f_date($date) {
		$date = strtotime($date);
		$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
		if(strpos("d M Y",'M')===false) return date("d M Y", $date);
		else return date(str_replace('M',$MonthNames[date('n',$date)-1],"d M Y"), $date);
	}
    public static function f_date_message($date) {
        $date = strtotime($date);
        $MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
        if(strpos("d M Y",'M')===false) return date("d M Y", $date);
        else return date(str_replace('M',$MonthNames[date('n',$date)-1],"d M в H:i"), $date);
    }

    public static function search()
    {

        if(isset($_POST['table']) && $_POST['table'] == "students" || $_POST['table'] == "public")
        {
            $table = $_POST['table'];

            $request = "%".$_POST['request']."%";

            $insert = "";
            $params = [];

            if(isset($_POST['params']))
            {
                $search_params = $_POST['params'];

                foreach ($search_params as $key => $value) {
                    if($value == "none") continue;
                    else {
                        $insert.= " AND $key = :$key";
                        $params[":".$key] = $value;
                    }
                }
            }



                //echo $insert."<br>".var_dump($params);
            if($_POST['request'] != " " || $insert != "")
            {
                $db = db::getConnection();

                if($table == "students")
                {
                    if($_POST['request']) $sql = 'SELECT * FROM `students` WHERE verification="true"'.$insert.' AND name LIKE :request';
                    else $sql = 'SELECT * FROM `students` WHERE verification="true"'.$insert;
                }
                else if($table == "public")
                {
                    $insert = substr($insert,4);
                    if($_POST['request']) {
                        if($insert != "") $insert = $insert." AND ";
                        $sql = 'SELECT * FROM `public` WHERE '.$insert.' name LIKE :request';
                    }
                    else {
                        if($insert != "") $insert = " WHERE ".$insert;
                        $sql = 'SELECT * FROM `public`'.$insert;
                    }
                }

                $result = $db->prepare($sql);// Используется подготовленный запрос

                //if($_POST['request']) $result->bindParam(':request', $request, PDO::PARAM_STR);

                if($_POST['request']) $result->bindParam(':request', $request, PDO::PARAM_STR);
                foreach ($params as $key => &$value) {
                    $result->bindParam($key, $value);
                }

                $result->setFetchMode(PDO::FETCH_ASSOC);
                $result->execute();// Выполнение коменды

                $search = "";$group_1 = 0;$group_2 = 0;
                while ($row = $result->fetch()) {
                    if($table == "students" && user::checkContact($row['id']) || $table=="public" && community::isPublic($row['id'])) {
                        $search['group_1'][$group_1] = $row['id'];
                        $group_1++;
                    }
                    else {
                        $search['group_2'][$group_2] = $row['id'];
                        $group_2++;
                    }
                }

                if(!$_POST['request'] && $insert == "")
                {
                    $data_load = "";
                    if($table == "students") $data_load = user::loadFriends();
                    else if($table == "public") $data_load = community::loadPublic();


                    if($data_load == "none") return "no_data";
                    else {
                        if($table == "students") $answer = self::listContactView($data_load );
                        else if($table == "public") $answer = community::listPublicView($data_load);

                        return $answer;
                    }
                }

                else {
                    if($search) {
                        $answer = "";
                        if($table == "students")
                        {
                            if(isset($search['group_1'])) $answer.= self::listContactView($search,'group_1');
                            if(isset($search['group_2'])) $answer.= self::listContactView($search,'group_2');
                        }
                        else if($table == "public")
                        {
                            if(isset($search['group_1'])) $answer.= community::listPublicView($search['group_1'],'group_1');
                            if(isset($search['group_2'])) $answer.= community::listPublicView($search['group_2'],'group_2');
                        }
                        return $answer;
                    }
                    else return "fail";
                }
            }
            else return "fail";
        }
        else return "fail";
    }
    public static function viewContactDialog($contact,$input){
        $html = "";
        if($contact != "none")
        {
            for($i=0;$i<count($contact);$i++) {
                $contact_info = user::userInfo($contact[$i]);
                if($contact_info)
                {
                    $attr = "";$name = "";$input_id = "";
                    switch ($input) {
                        case "radio": 
                            $input_id = "select_contact_".$contact_info["id"];
                            $name = "select_contact"; 
                            break;
                        case "checkbox": 
                            $input_id = "select_contacts_".$contact_info["id"];
                            $name = "select_contacts[]"; 
                            break;
                        default: 
                            $input_id = "";
                            $name = "";
                            break;
                    }

                    if($contact_info['settings'][0] == "" && $input == "checkbox") $attr = "disabled";

                    $html.="<div class='ac_contact ".$attr."'>
                        <div class='user_img size_40 link_profile' data-id-profile='".$contact_info["id"]."'>
                            <img src='template/image/students_photo/".$contact_info["avatar"]."'>
                        </div>
                        <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                        <input type='".$input."' name='".$name."' value='".$contact_info["id"]."' id='".$input_id."' ".$attr.">
                        <label for='".$input_id."'></label>
                    </div>";
                }
            }
            return $html;
        }
    }
    public static function viewInviteGroup($contact,$public="none"){
        $html = "";
        if($contact != "none")
        {
            if($public !="none") $community = community::publicInfo($public);
            for($i=0;$i<count($contact);$i++) {
                $insert = "";
                $position = "bottom";
                $contact_info = user::userInfo($contact[$i]);
                if($contact_info)
                {
                    $input_id = "select_contacts_".$contact_info["id"];


                    if($public == "none")
                    {
                        if($contact_info['settings'][0] == "")
                        {
                            $insert = "<div class='ac_info'>
                                <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <p class='ac_type' data-type='2'>Запретил приглашать</p>
                            </div>";
                        }
                        else {
                            $position = "top";
                            $insert = "<p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <input type='checkbox' name='select_contacts[]' value='".$contact_info["id"]."' id='".$input_id."'>
                                <label for='".$input_id."'></label>";
                        }
                    }
                    else {
                        if($contact_info['settings'][0] == "")
                        {
                            $insert = "<div class='ac_info'>
                                <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <p class='ac_type' data-type='2'>Запретил приглашать</p>
                            </div>";
                        }
                        else if(community::isPublic($community['id'],$contact_info['id'])){
                            $insert = "<div class='ac_info'>
                                <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <p class='ac_type' data-type='1'>Состоит в группе</p>
                            </div>";
                        }
                        else if(invite::isInvite($community['id'],$contact_info["id"]))
                        {
                            $insert = "<div class='ac_info'>
                                <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <p class='ac_type' data-type='2'>Приглашён</p>
                            </div>";
                        }
                        else {
                            if($community['type'] == "1" || $community['type'] == "3" || $community['type'] == "2" && $contact_info['specialty'] == $community['param'])
                            {
                                $position = "top";
                                $insert = "<p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                    <input type='checkbox' name='select_contacts[]' value='".$contact_info["id"]."' id='".$input_id."'>
                                    <label for='".$input_id."'></label>";
                            }
                            else $insert = "<div class='ac_info'>
                                <p class='link_profile' data-id-profile='".$contact_info["id"]."'>".$contact_info["name"]."</p>
                                <p class='ac_type' data-type='2'>Иная специальность</p>
                            </div>";
                        }
                    }

                    $attr = "";
                    if($position == "top") $attr = "position-top";
                    $text="<div class='ac_contact ".$attr."'>
                        <div class='user_img size_40 link_profile' data-id-profile='".$contact_info["id"]."'>
                            <img src='template/image/students_photo/".$contact_info["avatar"]."'>
                        </div>
                        ".$insert."
                    </div>";

                    if($position == "bottom") $html.=$text;
                    else $html = $text.$html;
                }
            }
            return $html;
        }
    }
    public static function listContactView($contact,$title = "")
    {
        $html = "";
        switch ($title) {
            case 'group_1': 
                $html.= '<p class="search_title">Ваши контакты</p>'; 
                $contact = $contact[$title];
            break;
            case 'group_2': 
                $html.= '<p class="search_title">Глобальный поиск</p>'; 
                $contact = $contact[$title];
                break;
            default: 
                $contact = $contact;
                break;
        }
        
        if($contact != "none")
        {
            for($i=0;$i<count($contact);$i++) 
            { 
                $border = "";
                if($i == count($contact)) $border = "style='border-bottom:none'";

                $contact_info = user::userInfo($contact[$i]);
                $style_link_dial = "";
                if(!$contact_info){
                    $contact_info['id'] = $contact[$i];
                    $contact_info["avatar"] = "no_avatar.jpg";
                    $contact_info["name"] = "Профиль удалён";
                    $contact_info["specialty"] = "неизвестно";
                    $style_link_dial = "data-disabled='true'";
                    $action = "remove";
                }
                else {
                    $link_dialog = "";
                    $dialog = dialog::isDialog($contact_info['id'],"students");
                    if($dialog) {
                        $dialog_id = dialog::isDialogLoad($contact_info['id'],"students");
                        $load_dialog = dialog::load("id_dialog",$dialog_id)[0];

                        $user = user::userInfo();
                        if(strpos($load_dialog['user_delete'], $user['id']." ") === false) $link_dialog = "<a href='#' class='open_dial' ".$style_link_dial.">Открыть диалог</a>";
                        else $link_dialog = "<a href='#' class='create_dial' ".$style_link_dial.">Создать диалог</a>";
                    }
                    else $link_dialog = "<a href='#' class='create_dial' ".$style_link_dial.">Создать диалог</a>";


                    $contact_info["specialty"] = spc::load($contact_info["specialty"])["abridged"]." (".$contact_info["course"]." курс)";
                    if(user::checkContact($contact_info['id'])) $action = "remove";
                    else $action = "add";
                }
                $html.="<div class='friend' data-id='".$contact_info['id']."'>
                    <input type='button' class='action_user' data-action='".$action."' data-id='".$contact_info["id"]."'>
                    <div class='user_img size_40 link' data-type='profile' data-id='".$contact_info["id"]."'>
                        <div class='status_user ".$contact_info["status"]."'></div>
                        <img src='template/image/students_photo/".$contact_info["avatar"]."'>
                    </div>
                    <div class='friend_info' $border>
                        <strong class='link' data-type='profile' data-id='".$contact_info["id"]."'>".$contact_info["name"]."</strong>
                        <p>".$contact_info["specialty"]."</p>
                        ".$link_dialog."
                    </div>
                </div>";
            }
        }
        return $html;
    }
    public static function resize($file, $w_dest,$h_dest,$coord_y,$coord_x,$trim_width)
    {
        $quality = 100;

        if ($file['type'] == 'image/jpeg') $source = imagecreatefromjpeg($file['tmp_name']);
        elseif ($file['type'] == 'image/png') $source = imagecreatefrompng($file['tmp_name']);
        else return false;

        $src = $source;

        $w_src = imagesx($src);
        $h_src = imagesy($src);


        $dest = imagecreatetruecolor($w_dest, $h_dest);
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

        $dest = imagecreatetruecolor($trim_width, $trim_width);
        imagecopyresampled($dest, $src, -$coord_x,-$coord_y, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

        imagejpeg($dest, $file['tmp_name'], $quality);

        $src = imagecreatefromjpeg($file['tmp_name']);
        $dest = imagecreatetruecolor(120, 120);
        imagecopyresampled($dest, $src,  0, 0, 0, 0, 120, 120, $trim_width, $trim_width);
        imagejpeg($dest, $file['tmp_name'], $quality);

        imagedestroy($dest);
        imagedestroy($src);

        return $file['tmp_name'];
        
    }

    public static function is_empty($question){
        return (!isset($question) || trim($question)==='');
    }

}