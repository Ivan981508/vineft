<input type="button" class="close">
<div id="admin_public" data-public-id="<?=$publicInfo['id'];?>">
    <div id="public_avatar" data-type="public"><img src="template/image/public_photo/<?=$publicInfo['avatar'];?>"></div>
    <div id="inform_public">
        <input type="text" id="edit_name_public" placeholder="Название группы" name="name" value="<?=$publicInfo['name'];?>">
        <div id="select_type_public" data-type="<?=$publicInfo['type'];?>" data-min="1" data-max="3" data-exception="2"></div>
    </div>
    <div class="clear"></div>
    <button class="button_2" id="edit_setting_public" data-value="Сохранить изменения">
        <div class="button_loading">
            <div class="bl">
                <div id="circleG_1" class="circleG"></div>
                <div id="circleG_2" class="circleG"></div>
                <div id="circleG_3" class="circleG"></div>
            </div>
        </div>
        <p>Сохранить изменения</p>
    </button>


    <div class="confirm_delete" data-del="public">
        <strong>Вы действительно хотите удалить сообщество?</strong>
        <p>После удаления, вы не сможете его восстановить!</p>
        <input type="button" class="close">
        <input type="button" class="confirm_no close"  value="Нет, я передумал">
        <a href="#" class="confirm_yes">Да, я всё решил</a>
    </div>
    <a href="#" id="delete_public">Удалить сообщество</a>
    <div id="public_student">
        <div class="list_contact"><?=$user_view;?></div>
    </div>
</div>  