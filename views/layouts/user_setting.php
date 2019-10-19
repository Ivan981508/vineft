<div id="user_setting">
    <div id="main_set">
        <strong>Настройки</strong>
        <?php if($user['verification'] == "false") { ?>
            <div id="mark_verif" class="mark_no_verif">
                <div class="mark"></div>
                <p>Подтвердите профиль<span>Подробнее</span></p>
            </div>
        <?php } ?>
        <div id="group_switch">
            <div class="switch">
                <p>Разрешить приглашать меня в группы</p>
                <input type="checkbox" id="set_0" data-id-param="0" <?=$user['settings'][0];?>>
                <label for="set_0"><i></i></label>
            </div>
            <div class="switch">
                <p>Соц. сети могут видеть только проверенные студенты</p>
                <input type="checkbox" id="set_1" data-id-param="1" <?=$user['settings'][1];?>>
                <label for="set_1"><i></i></label>
            </div>
            <div class="switch">
                <p>Скрывать статус ‘На сайте’</p>
                <input type="checkbox" id="set_2" data-id-param="2" <?=$user['settings'][2];?>>
                <label for="set_2"><i></i></label>
            </div>
        </div>
    </div>
    <p></p>
    <div id="rest_set">
        <div id="hover_network"></div>
        <strong>Вы в социальных сетях:</strong>
        <div id="group_network">
            <?php for($i=0;$i<count($user['social']);$i++) { 
                $name = $user['social'][$i];
                if($name['network'] == "vk") continue;
                else {
                    $addClass = "";
                    if($name['value'] == "") $addClass = "no_ntw";?>
                    <button type="button" data-name="<?=$name['network'];?>" class="<?=$addClass;?>">
                        <div class="loading"><div class="load_sw"></div></div>
                    </button>
            <?php } } ?>
        </div>
        <div class="clear"></div>
        <p class="text">Нажмите по иконке чтобы добавить или отредактировать</p>

        <div class="confirm_delete" data-del="profile">
            <strong>Вы действительно хотите покинуть нас?</strong>
            <p>Вы не сможете восстановить свои профиль после удаления!</p>
            <input type="button" class="close">
            <input type="button" class="confirm_no close" value="Нет, я передумал">
            <a href="#" class="confirm_yes">Да, я всё решил</a>
        </div>
        <a id="del_profile" href="#">Удалить профиль</a>
    </div>
</div>