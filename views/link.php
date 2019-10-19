<?php include ROOT.'/config/vk_params.php';
require_once ROOT.'/views/layouts/head.php';?>
<body id="bg_main">

    <div id="link_profile">
        <div id="lp_title">
            <a href="/">vINeft</a>
            <div id="lp_profile">
                <?php if(user::checkToken()) { ?>
                    <div id="lp_user">
                        <div class="user_img size_30">
                            <img src="/template/image/students_photo/<?=user::userInfo()['avatar'];?>">
                        </div>
                    </div>
                <?php } else { ?>
                    <div id="no_autoriz">
                        <p>Вы не авторизованы</p>
                        <a href="https://oauth.vk.com/authorize?client_id=<?=ID?>&display=page&redirect_uri=<?=URL?>&response_type=code">Вход в профиль</a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if($student) { 
            if($link_type == "user_link") {?>
                <div id="lp_user_view">
                    <div class="user_img size_120">
                        <img src="/template/image/students_photo/<?=$student['avatar'];?>">

                        <?php 
                        if(user::checkToken()) {
                            if(user::checkContact($student['id'])) {?>
                            <input type="button" class="action_user" data-action="remove" data-id="<?=$student['id'];?>">
                            
                            <?php } else if(!user::checkContact($student['id']) && $id != "my"){ ?>
                            <input type="button" class="action_user" data-action="add" data-id="<?=$student['id'];?>">
                        <?php } 
                        } ?>

                    </div>
                    <p id="user_name"><?=$student['name'];?></p>
                    <p id="user_specialty"><?=spc::load($student['specialty'])['name'];?>
                    <span><?=spc::load($student['specialty'])['abridged'];?> (<?=$student['course'];?> курс)</span></p>
                </div>
            <?php } else if($link_type == "social_link") { ?>
                <div id="error_permission">
                    <div class="user_img size_40">
                        <img src="/template/image/students_photo/<?=$student['avatar'];?>">
                    </div>
                    <p id="user_name"><?=$student['name'];?></p>
                    <p id="user_specialty">Разрешил(а) показывать ссылки на свои соц. сети только проверенным студентам</p>
                    <a href="#">Подтвердите свою учётную запись</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div id="error_link">
                <img src="/template/image/no_link_student.png">
                <p id="user_name">Студента с таким ID не существует</p>
                <p id="user_specialty">Воспользуйтесь поиском в разделе контакты</p>
            </div>
        <?php } ?>
    </div>
</body>
</html>