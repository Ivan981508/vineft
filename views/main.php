<?php require_once ROOT.'/views/layouts/head.php';?>

<body id="bg_main">
    <div id="popups">
        <input type="button" class="close">
        <div class="loading"><div class="load_sw"></div></div>
        <div id="insert_window"></div>
    </div>
    <div id="layout_messenger">
        <nav>
            <div class="user_img size_40 link" data-type="profile" data-id="my">
                <img src="template/image/students_photo/<?=$user['avatar'];?>">
            </div>    
            <ul>
                <li data-notice="0"><a href="dialogs" class="nav_active"></a></li>
                <li data-notice="0"><a href="contacts"></a></li>
                <li data-notice="0"><a href="group"></a></li>
                <li data-notice="0"><a href="setting"></a></li>
            </ul>
            <input type="button" id="close_session">
        </nav>
        <div id="column_list">   
            <div class="loading"><div class="load_sw"></div></div>    
            <div class="inset_cl"></div>
        </div>
        <div id="column_message">
            <div class="loading"><div class="load_sw"></div></div>  
            <div class="no_message" style="display: block;">
                <img src="template/image/dialogs.png">
                <p>Выберите диалог</p>
            </div>
            <div class="insert_message"></div>
            <div id="input_chat">
                <div id="stickers">
                    <ul>
                        <?php for($i=1;$i<=12;$i++) { ?>
                        <li data-sticker-id="<?=$i;?>"><img src="template/image/stickers/64/sticker_<?=$i;?>.png"></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="user_img size_30 link_profile" data-id-profile="my">
                    <img src="template/image/students_photo/<?=$user['avatar'];?>">
                </div> 
                <div id="input_panel">
                    <input type="button" id="view_stickers">
                    <input type="button" id="send_message">
                    <label>Отправить сообщение можно нажав 'enter'</label>
                </div>
                <textarea placeholder="Напишите сообщение" spellcheck="false"></textarea>
                <div id="status_edit">
                    <div id="status_anim">
                        <div class="sa_circle"></div>
                        <div class="sa_circle"></div>
                        <div class="sa_circle"></div>
                    </div>
                    <p>Набирает сообщение</p>
                </div>
            </div>
        </div>
        <div id="info_block">
            <div class="loading">
                <div class="load_sw"></div>
            </div>
            <div class="block"></div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        autosize($('textarea'));
        $(".scroll_content").niceScroll({
            cursorwidth:5,
            railalign:"right",
            cursorcolor:"#c3c8d7",
            cursorborderradius:"30px",
            cursorborder:"10px solid transparent",
            cursoropacitymin:0,
            railpadding:{left:0}
        });
        $("#page_chat").niceScroll({
            cursorwidth:5,
            railalign:"right",
            cursorcolor:"#c3c8d7",
            cursorborderradius:"20px",
            cursorborder:"10px solid transparent",
            cursoropacitymin:0,
            railpadding:{left:10}
        });


        informLoad("profile","my");

        notice_invite = parseInt('<?=$count_invite;?>');
        noticeInvite(1,0);
        console.log("notice_invite - "+notice_invite);

        unread_message = parseInt('<?=$count_unread;?>');
        unreadMessage(1,0);
        array_dialog = '<?=$array_dialog;?>'.split(",");
        my_id = '<?=$my_id;?>';
        load();
    });
    </script>
</body>
</html>