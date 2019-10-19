<form id="form_create" autocomplete="off">
    <strong>Создать</strong>
    <!--<div id="group_radio">
        <p>Что вы хотите создать?</p>
        <input type="radio" name="select_dial" id="sd_1" checked value="setting_ld">
        <label for="sd_1">Личный диалог</label>

        <input type="radio" name="select_dial" id="sd_2" value="setting_public">
        <label for="sd_2">Группа</label>
    </div>
    <div class="clear"></div>-->
    <div class="loading"><div class="load_sw"></div></div>
    <div id="dynamic_page">
        <?php if($page == "setting_ld") { ?>
            <div id="setting_ld">
                <p>Пригласите студентов</p>
                <div class="list_contact">
                    <?php if($contact == "none"){ ?>
                    <div class="error_column no_contact" style="display: block;">
                        <div class="img"></div>
                        <p>Список контактов пуст</p>
                    </div>
                    <?php } else {?>
                    <div class="ac_scroll"><?=functions::viewContactDialog($contact,"radio");?></div>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($page == "setting_public") { ?>
            <div id="setting_public">
                <div id="edit_public">
                    <p>Настройка</p>
                    <div id="public_avatar" data-type="public"><img src="template/image/no_photo.png"></div>
                    <div id="inform_public">
                        <input type="text" id="edit_name_public" placeholder="Название группы" name="name">
                        <div id="select_type_public" data-type="1" data-min="1" data-max="3" data-exception=""></div>
                    </div>   
                </div>
                <div class="clear"></div>
                <div id="invite_student">
                    <p>Пригласите студентов</p>
                    <div class="list_contact">
                        <div class="ac_scroll"><?=functions::viewInviteGroup($contact);?></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <button class="button_2" id="d_create" data-value="<?=$button_name;?>">
        <div class="button_loading">
            <div class="bl">
                <div id="circleG_1" class="circleG"></div>
                <div id="circleG_2" class="circleG"></div>
                <div id="circleG_3" class="circleG"></div>
            </div>
        </div>
        <p><?=$button_name;?></p>
    </button>
</form>
<script>
</script>