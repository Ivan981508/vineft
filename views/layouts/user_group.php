<div id="user_group">
    <div class="title_list">
        <div class="shell_search">
            <input type="checkbox" id="view_params">
            <label for="view_params"></label>

            <div id="params_search">
                <a href="#" id="view_all" data-value="group">Показать все сообщества</a>
                <div id="select_type_public" data-type="0" data-spec="<?=user::userInfo()['specialty'];?>" data-min="0" data-max="3" data-exception="3"></div>
            </div>

            <input type="text" class="search" placeholder="Поиск сообществ">
        </div>
        <input type="button" class="create" data-page="setting_public">
    </div>
    <?php if($public == "none" && $invite == "none"){ ?>
        <div class="error_column no_group" style="display: block;">
            <div class="img"></div>
            <p>Вы не состоите в сообществах</p>
        </div>
    <?php } ?>

    <div class="error_column no_search">
        <div class="img"></div>
        <p>Не удалось найти сообщества<br>по вашему запросу.</p>
    </div>
    <div class="scroll_content"><?=invite::listInviteView($invite).community::listPublicView($public,"group_1")?></div>
</div>
<script>
    invite = '<?=$count_invite;?>';
    console.log(invite);
</script>