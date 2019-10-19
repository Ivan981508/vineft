<?php

/**
 * Контроллер CartController
 */
class SiteController
{

    /**
     * Action для главной страницы
     */
    public function actionIndex()
    {
        if(user::checkToken()) {
            header("Location:/feed");
        }
        else header("Location:/autorization");
        return true;
    }
    public function actionFeed()
    {
        if(user::checkToken()) {
            $user = user::userInfo();

            $dialog = dialog::load();
            $my_id = user::userInfo()['id'];
            $array_dialog = "";
            $count_unread = 0;
            if($dialog != "none")
            {
                for($i=0;$i<count($dialog);$i++) $array_dialog.= $dialog[$i]['dialog_id'].",";
                $array_dialog = substr($array_dialog,0,-1);

                for($i=0;$i<count($dialog);$i++) $count_unread+=message::checkRead($dialog[$i]['dialog_id']);
            }
            $count_invite = invite::countInvite();
            require_once(ROOT . '/views/main.php');
        }
        else header("Location:/autorization");
        return true;
    }
    public function actionAutoriz()
    {
        require_once(ROOT . '/views/login.php');
        return true;
    }
    public function actionReg()
    {
        $spec = spc::load();
        require_once(ROOT . '/views/registration.php');
        return true;
    }
    public function actionAjax($name_script)
    {
        $loadReviews = ajax::$name_script();
        return true;
    }
}
