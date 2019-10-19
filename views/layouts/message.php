<div id="titile_dialog">
    <p id="name_dialog"><?=$data['name'];?></p>
    <?php if($type=="profile") { ?> <p id="status_user" class="<?=$data['status'];?>"><i></i><span></span></p><?php } else {?>
    <p id="td_user_public">(<?=$data['number_user'];?> человек)</p> <?php } ?>
    <div id="menu_dialog" data-hover="true" data-del="message" data-dialog-id="<?=$dialog_id;?>" data-user-id="<?=$data['id'];?>" data-type-dialog="<?=$type;?>">
        <input type="button" id="view_menu">
        <div id="menu" data-hover="true">
            <ul>
                <li data-type="clear_message">Очистить сообщения</li>
                <?php if($type == "public") { ?> <li data-type="leave_group">Покинуть группу</li> <?php } 
                else if($type == "profile") {?> <li data-type="delete_dialog">Удалить диалог</li> <?php } ?>
            </ul>
        </div>
        <div class="confirm_delete" data-del="">
            <strong></strong>
            <p></p>
            <input type="button" class="close">
            <input type="button" class="confirm_no close" value="Нет, я передумал">
            <a href="#" class="confirm_yes">Да, я всё решил</a>
        </div>
    </div>
</div>
<?php if($message == "none") {?>
<div class="no_message">
    <img src="template/image/dialogs.png">
    <p>Начните диалог</p>
</div>
<?php } ?>
<div id="page_chat">
    <div class="chat_scroll"><?=$html;?></div>
</div>
<script>
$(document).ready(function(){
    loadMessage = '<?=$load->param;?>';
    console.log(loadMessage);
    if(loadMessage == "none") $(".loading_message").remove();
    $("#page_chat").scroll(function(){
        if(!loading_message && loadMessage !="none")
        {
            if ($(this).scrollTop() <= 10) {
                loadMess();
                loading_message = true;
            }
        }
    });
    function loadMess(){
        $.post( "/ajax/loadMessage",{"from":loadMessage,"dialog_id":active_dialog}, function(data) {
            $(".loading_message").remove();
            $("#page_chat .chat_scroll").prepend(data.message);
            
            loadMessage = data.param;
            if(loadMessage == "none") $(".loading_message").remove();
            var new_height = $(".chat_scroll").height();

            var scroll = new_height-height_chat;
            console.log(height_chat);
            $("#page_chat").scrollTop(scroll);
            $("#page_chat").getNiceScroll().onResize();
            height_chat = new_height;
            loading_message = false;
        },"json");
    }
});
</script>