<form id="load_photo" data-param="<?=$param;?>">
    <div id="lp_part_1">
        <img src="template/image/<?=$bg_name;?>">
        <input type="file" id="b_load_photo" data-param="<?=$param;?>">
        <label for="b_load_photo">Выберите изображение</label>
        <div id="descript_load">
            <strong><?=$h_text['strong'];?></strong>
            <p><?=$h_text['p'];?></p>
            <p class="error"></p>
        </div>
    </div>

    <div class="loading">
        <div class="load_sw"></div>
        <p>Изображение загружается, на это может потребоваться некоторое время!</p>
    </div>
    <div id="lp_part_2" style="display: none;">
        <strong>Обрезка фото</strong>
        <p>Выбранная область будет показываться на Вашей странице.</p>
        <div id="uploaded_photo">
            <div id="selected_area">
                <div id="select_circle">
                    <img src="">
                </div>
                <div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>
                <div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>
                <div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>
                <div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>
            </div>
            <div id="mask_photo"></div>
            <img src="">
        </div>
        <input type="submit" class="button" value="Сохранить" id="save_load_photo">
        <a href="#" id="backward">Вернуться назад</a>
    </div>
</form>
<script>
$(document).ready(function(){
    $("#selected_area").draggable({ 
        containment: "#uploaded_photo",
        drag: function( event, ui ) {
            var top = ui.position.top;
            var left = ui.position.left;
            $("#select_circle img").css("transform","translate("+(-left+"px")+","+(-top+"px")+")");
            avatar_param['coord_y'] = top;
            avatar_param['coord_x'] = left;
        }
    });
    $("#selected_area").resizable({
        aspectRatio: true,
        containment: "#uploaded_photo",
        minWidth: 120,
        minHeight:120,
        handles: {
            'ne': '#nwgrip',
            'se': '#negrip',
            'sw': '#swgrip',
            'nw': '#segrip'
        },
        resize: function(event,ui){

            var top = ui.position.top;
            var left = ui.position.left;
            var width = $(this).width();

            $("#select_circle img").css("transform","translate("+(-left+"px")+","+(-top+"px")+")");

            avatar_param['trim_width'] = width;
        }
    });

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#lp_part_1").fadeOut(function(){
                    $("#lp_part_2").css("display","block");
                    $("#uploaded_photo img").attr('src',e.target.result);
                    optimizePhoto();
                });
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#b_load_photo").change(function(){ 
        $("#lp_part_1").css("opacity",0);
        $("#load_photo .loading").css("display","block");
        var param = $(this).attr("data-param");
        console.log("1-"+param);
        uploadPhoto(this,"check",param);

    });
    $("#backward").click(function(){
        $("#lp_part_2").fadeOut(function(){
            $("#lp_part_2").css("opacity",0);
            $("#lp_part_1").css("opacity",1);
            $("#lp_part_1").fadeIn();
        });
    });
    $("body").on("submit","#load_photo",function(e){
        e.preventDefault();
        var param = $(this).attr("data-param");
        uploadPhoto(this,"upload",param);
        console.log("2-"+param);
    });
    var i = 0;
    function uploadPhoto(el,type,param){
        var data = new FormData;
        data.append('avatar', $("#b_load_photo").prop('files')[0]);
        data.append('type', type);
        if(type == "upload")
        {
            data.append('coord_y', avatar_param['coord_y']);
            data.append('coord_x', avatar_param['coord_x']);
            data.append('trim_width', avatar_param['trim_width']);
            data.append('img_width', avatar_param['img_width']);
            data.append('img_height', avatar_param['img_height']);
            $("#lp_part_2").css("display","none");
            $("#load_photo .loading").css("display","block");
        }
        else showErrorLoad("hide","");


        $.ajax({
            url: '/ajax/uploadAvatarCheck',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (json) {
                //showErrorRegister("hide","");
                if(type == "check")
                {
                    $("#load_photo .loading").css("display","none");
                    if(json.status == "success") readURL(el);
                    else {
                        
                        $("#lp_part_1").css("opacity",1);
                        showErrorLoad("show",json.data);
                    }
                }
                else if(type == "upload")
                {
                    if(json.status == "success")
                    {
                        var nameFile = "";
                        if(param == "reg") {
                            data_reg['avatar'] = json.filename;
                            nameFile = data_reg['avatar'];
                        }
                        else if(param == "public"){
                            data_public['avatar'] = json.filename;
                            nameFile = data_public['avatar'];
                        }

                        if(param == "reg")
                        {
                            $("#user_avatar .user_img img").prop("src","/template/image/temporary/"+nameFile);
                            $("#user_avatar .user_img img").css("display","block");
                        }
                        else if(param == "public"){
                            $("#public_avatar img").prop("src","/template/image/temporary/"+nameFile);
                        }
                        $("#popups").fadeOut();
                        $("body").css("overflow","auto");
                        $("#load_photo .loading").css("display","none");
                    }
                    else {
                        $("#lp_part_2").css("display","block");
                    }
                }
            }
        });
    }
    function optimizePhoto()
    {
        var img = $('#uploaded_photo >img');  
        img.on('load', function(){
            img.removeAttr("width").removeAttr("height").css({ width: "", height: "" });
            $("#lp_part_2").css("height","").removeAttr("class");

            //Узнаём размеры фото
            var img_width = img.width();
            if(img_width > 500) {
                img_width = 500;
                img.width(img_width);
            }
            
            $("#uploaded_photo > img,#select_circle img,#uploaded_photo").css("width",img_width);
            
            $("#lp_part_2").height($("#lp_part_2").height());
            var form_height = $("#lp_part_2").height();

            avatar_param['img_width'] = img_width;
            avatar_param['img_height'] = img.height();

            mediaScreen("#lp_part_2");
            $("#lp_part_2").css("opacity",1);
        });
        img.each(function() {
            var src = $(this).attr('src');
            $(this).attr('src', '');
            $(this).attr('src', src);
        }); 
    }
    function mediaScreen(obj){
        var form_height = $(obj).height();
        $("#lp_part_2").removeAttr("class");
        if($(window).height() < form_height) $("#lp_part_2").addClass("static_pos");
        else $("#lp_part_2").addClass("center_div");
    }
    $(window).resize(function(){
        if($("#lp_part_2").css("display") == "block")
        {
            mediaScreen("#lp_part_2");
        }
    });

    function showErrorLoad(type,text)
    {
        if(type == "hide") $("#load_photo .error").fadeOut();
        else {
            $("#load_photo .error").fadeOut(500,function(){
                $("#load_photo .error").html(text);
                $("#load_photo .error").fadeIn(500);
            });
        }
    }
});
</script>