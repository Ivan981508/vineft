<?php

class LinkController
{
    public function actionSocial($id,$param)
    {
        $student = user::userInfo($id);
        $my_profil = user::userInfo();
        $link_type = "social_link";

        if($student['settings'][1] == "" || $student['settings'][1] == "checked" && $my_profil['verification'] == "true")
        {
            $id_social = $student[$param];
            if($param == "vk") $id_social = "id".$id_social;
            $link = "https://".$param.".com/".$id_social;
            header("Location:".$link);
        }
        else {
            include ROOT . '/views/link.php';
        }
    }
    public function actionProfile($id)
    {
        $student = user::userInfo($id);
        $link_type = "user_link";
        include ROOT . '/views/link.php';
    }
}