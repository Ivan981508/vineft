<?php

/**
 * Контроллер CartController
 */
class UserController
{

    public function actionIndex()
    {
        if(user::checkToken()) {
            $card = json_decode(card::userCard());

            if($card->status == "success") $card_id = $card->data->id;
            else $card_id = 0;

            //Проверка накоплений
            server::checkEdge();
            require_once(ROOT . '/views/lk.php');
        }
        else header("Location:/");
        return true;
    }
    public function actionLogin()
    {
        $data = user::autorization();
        if($data == "error") header("Location:/error");
        else if($data == "success") header("Location:/feed");
        else if($data == "unregister") header("Location:/registration");
        return true;
    }
    public function actionCreate()
    {
        if(isset($_SESSION['sl_token']) && $_SESSION['sl_token'][1] == 'create_account') require_once(ROOT . '/views/layouts/setting_user.php');
        else header("Location:/");
        return true;
    }
}
