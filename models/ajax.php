<?php


class ajax
{
    public static function register(){
        if(count($_POST) == 10)
        {
            $name = $_POST['name'];
            $avatar = $_POST['avatar'];
            $surname = $_POST['surname'];
            $birthday = $_POST['birthday'];
            $city = $_POST['city'];
            $stud_ticket = $_POST['stud_ticket'];
            $specialty = $_POST['specialty'];
            $course = $_POST['course'];
            $pol = $_POST['pol'];
            $about_me = $_POST['about_me'];
            $answer = user::createAccount($name,$avatar,$surname,$birthday,$city,$stud_ticket,$specialty,$course,$pol,$about_me);

            $layout = file_get_contents(ROOT.'/views/registration/notice_register.php');
            if($answer == "success") $answer = ['status'=>"success","data"=>$layout];
            else $answer = ['status'=>"error","data"=>$answer];
        }
        else $answer = ['status'=>"fail","data"=>"Регистрация не может быть продолжена!"];
        echo json_encode($answer);
    }
    public static function ru_stage_1(){
        require_once(ROOT . '/views/registration/personal_data.php');
    }
    public static function loadPage(){
        if(user::checkToken()) {
            $page = $_POST['page'];
            if($page == "contacts")
            {
                $contact = user::loadFriends();
                $spec = spc::load();
            }
            else if($page == "setting")
            {
                $user = user::userInfo();
            }
            else if($page == "dialogs")
            {
                $dialog = dialog::load();
            }
            else if($page == "group") 
            {
                $public = dialog::load("my_public");
                $invite = invite::load();
                $count_invite = count($invite);
            }
            require_once(ROOT . '/views/layouts/user_'.$page.'.php');
        }
    }
    public static function rightColumn(){
        if(user::checkToken()) {
            $type = $_POST['type'];
            $id = $_POST['id'];

            if($type == "profile"){
                $my_id = user::userInfo()['id'];
                if($my_id == $id || $id == "my") {
                    $id = "my";
                    $user = user::userInfo();
                }
                else $user = user::userInfo($id);
                require_once(ROOT . '/views/layouts/user_page.php');
            }
            else if($type == "public"){
                $user = user::userInfo();
                $public = community::publicInfo($id);
                $count_user = count($public['users']);
                if($count_user > 5)
                {
                    $hover = "true";
                    $width = 'width: calc(30px * 5 - (10px * 5) + 16px)';
                    $count_user = 5;
                }
                else {
                    $hover = "false";
                    $width = "width: calc(30px * $count_user - (10px * $count_user) + 16px)";
                }

                $user_view = "";
                if($public['users'] != "none")
                {
                    for($i=0;$i<count($public['users']);$i++) { 
                        $student = user::userInfo($public['users'][$i]);
                        $user_view.= '<div class="user_img link" data-type="profile" data-id="'.$student["id"].'">
                            <img src="template/image/students_photo/'.$student["avatar"].'">
                        </div>';
                    }
                }
                $action_button = "";
                if(community::isPublic($public['id'])) $action_button = '<input type="button" id="group_info" value="Покинуть">';
                else {
                    if($public['type'] == "1" || $public['type'] == "2" && $user['specialty'] == $public['param']) $action_button = '<input type="button" id="group_info" value="Вступить">';
                    else $action_button = '<input type="button" id="group_info" disabled value="Не доступно">';

                }
                require_once(ROOT . '/views/layouts/group_page.php');
            }
        }
    }
    public static function actionContact(){
        $action = $_POST['action'];
        $id = intval($_POST['id']);
        if(user::actionContact($action,$id)) echo "success";
        else echo "fail";
    }
    public static function search(){
        echo functions::search();
    }
    public static function viewAll(){
        if(user::checkToken()) {
            $value = $_POST['value'];
            $my_id = user::userInfo()['id'];

            if($value == "contacts") $sql = 'SELECT * FROM `students` WHERE verification="true" AND id NOT IN('.$my_id.')';
            else if($value == "group") $sql = 'SELECT * FROM `public` WHERE 1';

            $db = db::getConnection();
            $result = $db->query($sql);

            $data = [];
            for($i=0;$row = $result->fetch();$i++) $data[$i] = $row['id'];

            if($value == "contacts") $answer = functions::listContactView($data);
            else if($value == "group") $answer = community::listPublicView($data);
            echo $answer;
        }
    }
    public static function exitProfile(){
        if(user::checkToken()) unset($_SESSION['vneft_token']);
    }
    public static function uploadAvatarCheck(){

        if(isset($_FILES['avatar']))
        {
            $type = $_POST['type'];

            $file = $_FILES['avatar'];
            $info_photo = getimagesize($file["tmp_name"]);
            $width = $info_photo[0];
            $height = $info_photo[1];

            if($file['type'] == "image/jpeg" || $file['type'] == "image/png")
            {
                if($height > $width*2 || $width > $height*2) $answer = ["status"=>"fail","data"=>"Одна из сторон изображения превышает другую более чем в 2 раза!"];
                else {
                    if($file['size'] > 1048576) $answer = ["status"=>"fail","data"=>"Изображение должно весить не более 1мб"];
                    else {
                        if($type == "check") $answer = ["status"=>"success"];
                        else if($type == "upload"){
                            $tmp_name = functions::resize($_FILES['avatar'], $_POST['img_width'], $_POST['img_height'], $_POST['coord_y'], $_POST['coord_x'], $_POST['trim_width']);
                            $filename = (time()+86400).".jpg";
                            if (move_uploaded_file($tmp_name,ROOT."/template/image/temporary/".$filename)) $answer = ["status"=>"success","filename"=>$filename];
                            else $answer = ["status"=>"fail","data"=>"Не удалось загрузить изображение"];
                        }
                    }
                }
            }
            else $answer = ["status"=>"fail","data"=>"Доступные форматы - jpg,png"];
        }
        else $answer = ["status"=>"fail","data"=>"Вы не выбрали фотографию"];
        echo json_encode($answer);
    }
    public static function editNtw(){
        if(user::checkToken()) {
            $name = $_POST['name'];

            $user = user::userInfo();
            include ROOT."/views/layouts/hover_network.php";
        }
    }
    public static function updateNtw(){
        if(user::checkToken()) {
            $name = $_POST['name'];
            $id = $_POST['id'];
            user::update([$name],[$id]);
        }
    }
    public static function confirmDelete(){
        $type = $_POST['type'];
        if($type == "profile") user::delete();
        else if($type == "public") {
            $public_id = $_POST['public_id'];
            $answer = community::deletePublic($public_id);
            var_dump($answer);
        }
        else if($type == "clear_message")
        {
            $public_id = $_POST['public_id'];
            message::deleteMessage($public_id);
        }
        else if($type == "leave_group")
        {
            $public_id = $_POST['public_id'];
            echo json_encode(community::actionPublic($public_id));
        }
        else if($type == "delete_dialog")
        {
            $public_id = $_POST['public_id'];
            dialog::deleteDialog($public_id);
        }

    }
    public static function updateSet(){
        $id_param = intval($_POST['id_param']);
        echo var_dump(user::updateSetting($id_param));
    }

    public static function formCreate(){
        $page = $_POST['page'];
        $button_name = "Создать";
        if($page == "setting_ld") $button_name = "Создать диалог";
        else if($page == "setting_public") $button_name = "Создать группу";
        $contact = user::loadFriends();
        include ROOT."/views/layouts/form_create.php";
    }

    public static function createDialog(){
        if($_POST){
            if(isset($_POST['page']))
            {
                if($_POST['page'] == "setting_ld")
                {
                    if(!isset($_POST['select_contact'])) $answer = ['status'=>"fail","type"=>"Вы не выбрали студента"];
                    else {
                        $user = user::userInfo();
                        $answer = dialog::createDialog($user['id'],$_POST['select_contact'],'students');
                    }
                }
                else if($_POST['page'] == "setting_public")
                {
                    if($_POST['name'] == "") $answer = ['status'=>"fail","type"=>"Напишите название сообщества"];
                    else {
                        $select_contacts = "";
                        if(isset($_POST['select_contacts'])) $select_contacts = $_POST['select_contacts'];
                        else $select_contacts = [];

                        $answer = community::createPublic($_POST['name'],$_POST['avatar'],$_POST['type'],$select_contacts);
                    }
                }
            }
        }
        //echo json_encode($answer);
        echo json_encode($answer);
    }

    public static function loadPhoto(){
        $param = $_POST['param'];
        $bg_name = "";
        $h_text = [];
        if($param == "reg") {
            $bg_name = "male_aside.jpg";
            $h_text['strong'] = "Загрузите личное <span>фото</span>";
            $h_text['p'] = "Загрузите свою фотографию. Изображение должно быть ваше личное. Лицо должно занимать не менее 75% изображения. Вы должны обязательно загрузить свою фотографию, для того чтобы другие студенты могли вас узнавать.";
        }
        else {
            $bg_name = "bg_lp_avatar.png";
            $h_text['strong'] = "Загрузите фото <span>для группы</span>";
            $h_text['p'] = "Загрузите свою фотографию, чтобы она отображала тематику вашего сообщества. Студентам будет проще понять тематику вашей группы, при поиске!";
        }
        require_once(ROOT . '/views/layouts/load_photo.php');
    }

    public static function invite_list(){
        $contact = user::loadFriends();
        $public_id = intval($_POST['public_id']);

        $button = "";
        if($contact == "none") $button = "disabled";
        require_once(ROOT . '/views/layouts/invite_to_group.php');
    }

    public static function setting_group(){
        if(isset($_POST['public']))
        {
            $user = user::userInfo();
            $publicInfo = community::publicInfo($_POST['public']);

            if($publicInfo['admin'] == $user['id'])
            {

                $user_view = "";
                for($i=0;$i<count($publicInfo['users']);$i++) { 
                    if($publicInfo['users'][$i] == $user['id']) continue;
                    else {
                        $student = user::userInfo($publicInfo['users'][$i]);
                        $user_view.= '<div class="user_public" data-id="'.$student["id"].'">
                            <button>Удалить из группы</button>
                            <div class="user_img size_30">
                                <img src="template/image/students_photo/'.$student["avatar"].'">
                            </div>
                            <p>'.$student["name"].'</p>
                        </div>';
                    }
                }
                require_once(ROOT . '/views/layouts/admin_public.php');
            }
        }
    }
    public static function excludeUserPublic()
    {
        $student = $_POST['student'];
        $public = $_POST['public'];

        $user = user::userInfo();
        $publicInfo = community::publicInfo($_POST['public']);

        if($publicInfo['admin'] == $user['id'])
        {
            if(community::leaveUser($student,$public)) echo "success";
            else echo "fail";
        }
    }
    public static function updatePublic()
    {
        if(isset($_POST["public"]) && isset($_POST["avatar"]) && isset($_POST["name"]) && isset($_POST["type"]))
        {
            $public = $_POST["public"];
            $avatar = $_POST["avatar"];
            $name = $_POST["name"];
            $type = $_POST['type'];

            if($name == "") $answer = ["status"=>"fail","type"=>"Напишите название"];
            else {
                if($type == "") $answer = ["status"=>"fail","type"=>"Выберите тип"];
                else $answer = community::updateInfo($public,$avatar,$name,$type);
            }
        }
        else $answer = ['status'=>"fail","type"=>"Неверные параметры"];
        //echo json_encode($answer);
        echo json_encode($answer);
    }
    public static function actionPublic(){
        $public_id = $_POST['public_id'];
        echo json_encode(community::actionPublic($public_id));
    }
    public static function inviteContact(){
        if(isset($_POST["public_id"]) && isset($_POST["contact"]))
        {
            $public_id = $_POST['public_id'];
            $contact = $_POST['contact'];
            $result = invite::inviteContact($public_id,$contact);
        }
        else $result = ['status'=>"fail"];
        echo json_encode($result);
    }
    public static function loadDialog(){
        $id = intval($_POST['id']);
        $type = $_POST['type'];

        $data = "";
        if($type == "public") $data = community::publicInfo($id);
        else if($type == "profile") $data = user::userInfo($id);

        $dialog_id = $_POST['dialog_id'];
        $load = (object)message::load($dialog_id,"initial");
        $message = $load->message;
        $param = $load->param;
        $html = message::viewMessage($message);


        //Пометим сообщения как прочитанные
        message::readMessage($dialog_id);

        $clear_button = "";
        if($message == "none") $clear_button = "disabled";
        require_once(ROOT . '/views/layouts/message.php');
    }
    public static function loadMessage(){
        $from = intval($_POST['from']);
        $dialog_id = $_POST['dialog_id'];
        $load = (object)message::load($dialog_id,$from);
        $html = message::viewMessage($load->message);
        $answer = json_encode(['message'=>$html,'param'=>$load->param]);
        echo $answer;
    }
    public static function userDialogID(){
        $user_id = $_POST['user_id'];
        $dialog_id = dialog::isDialogLoad($user_id,"students");
        echo $dialog_id;
    }
    public static function saveMessage(){
        if(isset($_POST['dialog_id']) && isset($_POST['message']) && isset($_POST['type']))
        { 
            $dialog_id = $_POST['dialog_id'];
            $user_id = user::userInfo()['id'];
            $type = $_POST['type'];
            $message = $_POST['message'];
            $result = message::saveMessage($dialog_id,$user_id,$message,$type);

            if(!$result) $answer = ['status'=>"fail"];
            else {
                $load_dialog = dialog::load("id_dialog",$dialog_id)[0];
                if($load_dialog['type'] == "students")
                {
                    if(strpos($load_dialog['user_delete'], $load_dialog['client']." ") === false) $answer = ['status'=>"success","id_message"=>$result,"id_user"=>"none"];
                    else {
                        $new_user_delete = preg_replace("/(".$load_dialog['client']." )/", '', $load_dialog['user_delete']);
                        dialog::update($dialog_id,['user_delete'],[$new_user_delete]);
                        $answer = ['status'=>"success","id_message"=>$result,"id_user"=>$load_dialog['client']];
                    }
                }
                else $answer = ['status'=>"success","id_message"=>$result,"id_user"=>"none"];
            }
        }
        else $answer = ['status'=>"fail"];
        echo json_encode($answer);
    }
    public static function viewMessage(){
        if(isset($_POST['id']) && isset($_POST['dialog_id']))
        {
            //Пометим сообщения как прочитанные
            message::readMessage($_POST['dialog_id']);

            $id = $_POST['id'];
            $result = message::viewOneMessage($id);

            if($result != "none") echo $result;
            else echo "fail";
        }
    }
    public static function viewDialogOne(){
        if(isset($_POST['dialog_id']) && isset($_POST['unread']))
        {
            $result = dialog::viewDialogOne($_POST['dialog_id'],$_POST['unread']);
            if(!$result) echo "fail";
            else echo $result;
        }
    }

    public static function viewInviteOne(){
        if(isset($_POST['invite']))
        {
            $invite = $_POST['invite'];
            $html = invite::viewInviteOne($invite);
            echo $html;
        }
    }
    public static function deleteInvite(){
        if(isset($_POST['public']))
        {
            $public = $_POST['public'];
            $result = invite::deleteInvite($public);
            if($result) echo "success";
            else echo "fail";
        }
        else echo "fail";
    }
}