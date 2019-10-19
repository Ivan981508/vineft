<div id="setting_public">
    <div id="edit_public">
        <p>Настройка</p>
        <div id="public_avatar" data-type="public"><img src="template/image/no_photo.png"></div>
        <div id="inform_public">
            <input type="text" id="edit_name_public" placeholder="Название группы" name="name">
            <div id="select_type_public" data-type="1"></div>
        </div>   
    </div>
    <div class="clear"></div>
    <div id="invite_student">
        <p>Пригласите студентов</p>
        <div class="list_contact">
            <!--<div class="ac_scroll"><?=functions::viewContactDialog($contact,"checkbox");?></div>-->
            <div class="ac_scroll"><?=functions::viewInviteGroup($contact);?></div>
        </div>
    </div>
</div>