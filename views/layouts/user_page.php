<div id="user_page">      
    <h1>Vineft</h1>  
    <div class="user_img size_120">
        <div class="status_user <?=$user['status'];?>"></div>
        <img src="template/image/students_photo/<?=$user['avatar'];?>">
        <?php if(user::checkContact($user['id'])) {?>
            <input type="button" class="action_user" data-action="remove" data-id="<?=$user['id'];?>">
        <?php } else if(!user::checkContact($user['id']) && $id != "my"){ ?>
            <input type="button" class="action_user" data-action="add" data-id="<?=$user['id'];?>">
        <?php } ?>
    </div>
    <input type="button" id="view_info" value="Подробнее">
    <div id="detailed_profile">
        <p id="in_netw">В сети <br><span><?=$user['date'];?></span></p>
        <strong>Личная информация</strong>
        <div id="group_info_user">
            <p id="user_city">Город: <span><?=$user['city'];?></span></p>
            <p id="user_spec">Специальность: <span><?=spc::load($user['specialty'])['name'];?></span></p>
            <p id="user_interes">Интересы: <span><?=$user['about_me'];?></span></p>
            <p id="user_date_bird">Дата рождения: <span><?=$user['birthday'];?></span></p>
        </div>
        <?php if($user['verification'] == "true")
        {
            $class = "mark_verif";
            $text = "Подтверждён";
        }
        else {
            $class = "mark_no_verif";
            $text = "Не подтверждён";
        } ?>
        <div id="mark_verif" class="<?=$class;?>">
            <div class="mark"></div>
            <p>Профиль<span><?=$text;?></span></p>
        </div>
    </div>
    <p id="user_name"><?=$user['name'];?></p>
    <p id="user_specialty"><?=spc::load($user['specialty'])['name'];?></p>

    <div id="user_network" style="width: calc(40px * <?=$user['count_social'];?>)">
        <?php for($i=0;$i<count($user['social']);$i++) {
            if($user['social'][$i]['value'] == "") continue;
            $link= "social/".$user['id']."/".$user['social'][$i]['network']; ?>
            <a href="<?=$link;?>" target="_blank" data-name="<?=$user['social'][$i]['network'];?>"></a>
        <?php } ?>
    </div>
</div>