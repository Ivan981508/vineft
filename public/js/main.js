$(document).ready(function(){
    var socket = io.connect('http://45.90.32.110:8008');
	window.data_reg = [];
    window.data_public = [];
    window.pageCreate = "none";
    data_public['avatar'] = "none";
    data_reg['avatar'] = "none";
    window.informLoad = informLoad;
    window.informBlock = [];
    window.nav = "";
    window.invite = 0;
    window.search = false;
    window.params_search = {};
    params_search['specialty'] = "none";
    params_search['course'] = "none";
    params_search['pol'] = "none";

    params_search['type'] = "none";
    params_search['param'] = "none";

    window.avatar_param = [];
    avatar_param['coord_y'] = 0;
    avatar_param['coord_x'] = 0;
    avatar_param['trim_width'] = 120;
    avatar_param['img_width'] = 0;
    avatar_param['img_height'] = 0;

    window.params_status = false;
    window.params_hover = false;
    window.expandSearch = expandSearch;
    var timer;
    var table_search = "none";
    var actionInvite = false;

    window.loadMessage = 0;
    window.height_chat = 0;

    window.active_dialog = 0;
    window.active_dialog_type = 0;
    window.array_dialog = "";
    window.load = load;

    window.my_id = 0;
    window.create_dialog = "none";
    window.unread_message = 0;
    window.notice_invite = 0;
    window.unreadMessage = unreadMessage;
    window.noticeInvite = noticeInvite;
    window.loading_message = true;

   	$("#main_page input").click(function(){
		var link = $(this).parent('#main_page').attr("data-link");
		document.location = link;
	});
    $("#resume_reg").click(function(){
    	showErrorRegister("hide","");
    	var error = true;

    	data_reg['name'] = $("input[data-id='name']").val();
    	data_reg['surname'] = $("input[data-id='surname']").val();
    	data_reg['birthday'] = $("input[data-id='birthday']").val();
    	data_reg['city'] = $("input[data-id='city']").val();

    	data_reg['stud_ticket'] = $(".group_input[data-name='number_ticket'] input").val();
    	data_reg['specialty'] = $("#select_spec").val();
    	data_reg['course'] = $("#select_course").val();
    	data_reg['pol'] = $("input[name='select_male']:checked").val();

    	if (typeof data_reg['pol'] === 'undefined' || data_reg['name'] == "" || data_reg['surname'] =="" || data_reg['birthday'] == ""
    	|| data_reg['city'] == "" || data_reg['stud_ticket'] == "" || data_reg['specialty'] == null || data_reg['course'] == null) error = false;
    	
    	if(error)
    	{
	    	$("#dynamic_layouts").css("display","none");
            $("#form_register .loading").css("display","block");
	    	$.post( "/ajax/ru_stage_1", function(data) {
				$("#dynamic_layouts").html(data);
                $("#form_register .loading").css("display","none");
				$("#dynamic_layouts").fadeIn();
			});
		}
		else showErrorRegister("show","Некоторые поля не были заполнены!");
    });

    $("body").on("click","#button_register",function(){
        showErrorRegister("show","");
    	data_reg['about_me'] = $("#personal_data textarea").val();
    	if(data_reg['about_me'] == "") showErrorRegister("show","Вы не написали о себе и своих интересах!");
        else if(data_reg['avatar'] == "none") showErrorRegister("show","Вы не загрузили фотографию");
        else 
    	{
            $("#dynamic_layouts").css("display","none");
            $("#form_register .loading").css("display","block");
    		$.ajax({
	            url:"/ajax/register",
	            dataType: 'json',
	            method: "POST",
	            data:{
	                name:data_reg['name'],
                    avatar:data_reg['avatar'],
	                surname:data_reg['surname'],
	                birthday:data_reg['birthday'],
	                city:data_reg['city'],
	                stud_ticket:data_reg['stud_ticket'],
	                specialty:data_reg['specialty'],
	                course:data_reg['course'],
	                pol:data_reg['pol'],
	                about_me:data_reg['about_me'],
	            },
	            success: function(json){
                    console.log(json);
	                if(json.status == "success") {
                        $("#dynamic_layouts").html(json.data);
                        $("#dynamic_layouts").css("display","block");
                        $("#form_register .loading").css("display","none");

	                }
	                else showErrorRegister("show",json.data);
	            }
	        });
    	}
    });
    function showErrorRegister(type,text)
    {
        if(type == "hide") $("#form_register .error").fadeOut();
        else {
            $("#form_register .error").fadeOut(500,function(){
                $("#form_register .error").html(text);
                $("#form_register .error").fadeIn(500);
            });
        }
    }

    function informLoad(type,id){
        $("#info_block .block").html("");
        $("#info_block .loading").css('display',"block");
        $("#info_block").attr("data-view",type);
        $.ajax({
            url:"/ajax/rightColumn",
            method: "POST",
            data:{
                type:type,
                id:id
            },
            success: function(data){
                $("#info_block .loading").css('display',"none");
                $("#info_block .block").html(data);
                informBlock["type"] = type;
                informBlock["id"] = id;
            }
        });
    }

    loadPage("dialogs");
    $("#layout_messenger nav ul li a").click(function(e){
        e.preventDefault();
        loadPage($(this).attr("href"));
    });

    function loadPage(page)
    {
        $("#column_list .inset_cl").html("");
        $("#column_list .loading").css("display","block");

        params_search['specialty'] = "none";
        params_search['course'] = "none";
        params_search['pol'] = "none";

        params_search['type'] = "none";
        params_search['param'] = "none";
        $.ajax({
            url:"/ajax/loadPage",
            method: "POST",
            data:{
                page:page
            },
            success: function(data){
                $("#layout_messenger nav ul li a").removeClass("nav_active");
                $("#layout_messenger nav ul li a[href='"+page+"']").addClass("nav_active");

                $("#column_list .loading").css("display","none");
                $("#column_list .inset_cl").html(data);
                nav = page;
                
                if(nav == "dialogs") {
                    if(create_dialog != "none")
                    {
                        clickDialog(create_dialog);
                        create_dialog = "none";
                    }

                    if(active_dialog != 0)
                    { 
                        $(".dialog[dialog_id='"+active_dialog+"']").removeClass("select_dialog");
                        $(".dialog[dialog_id='"+active_dialog+"']").addClass("select_dialog");
                    }
                }
                switch(nav){
                    case 'dialogs': title = "Диалоги";break;
                    case 'contacts': 
                        search_table = "students";
                        title = "Контакты";
                        break;
                    case 'group': 
                        search_table = "public";
                        title = "Сообщества";
                        break;
                    case 'setting': title = "Настройки";break;
                    default: title = "Vineft";break;
                }
                $("title").text(title);
                $(".scroll_content").niceScroll({
                    cursorwidth:5,
                    railalign:"right",
                    cursorcolor:"#c3c8d7",
                    cursorborderradius:"30px",
                    cursorborder:"0px solid transparent",
                    cursoropacitymin:0,
                    railpadding:{left:0}
                });
            }
        });
    }
    $("body").on("click",".link",function(){
        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        informLoad(type,id);
    });
    $("body").on("click",".action_user",function(){
        var action = $(this).attr("data-action");
        var id = $(this).attr("data-id");
        $.ajax({
            url:"/ajax/actionContact",
            method: "POST",
            data:{
                action:action,
                id:id
            },
            success: function(data){
                if(data == "success")
                {
                    if(nav == "contacts" && !search && action == "remove") $("#column_list .friend[data-id='"+id+"']").remove();

                    if(action == "remove") action = "add";
                    else action = "remove";

                    $(".action_user[data-id='"+id+"']").attr("data-action",action);
                }
            }
        });
    });
    $("#close_session").click(function(){
        $.post( "/ajax/exitProfile", function() {
            window.location.reload();
        });
    });

    $("body").on("click","#load_avatar input,#public_avatar",function(){
        var type = $(this).attr("data-type");

        /*var data = "";
        if(type == "reg") data = data_reg['avatar'];
        else if(type == "public") data = data_public['avatar'];
        
        $("#popups").css("display","block");
        $("body").css("overflow","hidden");
        if(data == "none")
        {
            $("#lp_part_1").css("display","block !important");
            $("#lp_part_2").css("display","none !important");
        }
        else {
            $("#lp_part_1").css("display","none !important");
            $("#lp_part_2").css("display","block !important");
        }*/
        $("#insert_window").html("");
        $("#insert_window").css("display","none");
        $("#popups .loading").css("display","block");
        $("#popups").css("display","block");
        $.post("/ajax/loadPhoto", {param:type},function(data){
            $("#insert_window").html(data);
            $("#popups .loading").css("display","none");
            $("#insert_window").css("display","block");
        });
    });


    $("body").on("click","#group_network button",function(){
        clearTimeout(timer);
        var name = $(this).attr("data-name");
        $("#hover_network").css("display","none");
        $("#hover_network").attr("data-name",name);
        $("button[data-name='"+name+"'] .loading").css("display","block");
        $("#hover_network").removeClass("color_before");
        $.ajax({
            url: '/ajax/editNtw',
            data: {
                "name":name
            },
            type: 'POST',
            success: function (data) {
                $("button[data-name='"+name+"'] .loading").css("display","none");
                $("#hover_network").html(data);
                $("#hover_network").fadeIn();
            }
        });
    });
    $("body").on("click",".close",function(){
        if($(this).parent('#dynamic_layout').attr("id") == "dynamic_layout") informLoad(informBlock["type"],informBlock["id"]);
        $(this).parent("div").css("display","none");
    });
    $("body").on("click",".save_ntw",function(){
        var name = $(this).parent("div").attr("data-name");
        var id = $(".edit_id").val();
        $.ajax({
            url: '/ajax/updateNtw',
            data: {
                "name":name,
                "id":id
            },
            type: 'POST',
            success: function (data) {
                $(".save_ntw").css("background-color","#6ce460");
                $(".save_ntw").prop("value","Изменено");
                $("button[data-name='"+name+"']").removeClass("no_ntw");
                $("#hover_network").addClass("color_before");
                timer = setTimeout(function(){
                    $("#hover_network").css("display","none");
                }, 1000);
            }
        });
    });
    $("body").on("click","#clear_message",function(){
        $("#td_input .confirm_delete").fadeIn();
    });
    $("body").on("click","#del_profile",function(){
        $("#user_setting .confirm_delete").fadeIn();
    });

    $("body").on("click","#delete_public",function(e){
        e.preventDefault();
        $("#admin_public .confirm_delete").fadeIn();
    });
    $("body").on("click",".confirm_yes",function(){
        var type = $(this).parents(".confirm_delete").attr("data-del");
        if(type == "clear_message")
        {
            var id = $(this).parents("#menu_dialog").attr("data-dialog-id");
            var user_id = $(this).parents("#menu_dialog").attr("data-user-id");
            var dialog_type = $(this).parents("#menu_dialog").attr("data-type-dialog");
        }
        else if(type == "delete_dialog")
        {
            var id = $(this).parents("#menu_dialog").attr("data-dialog-id");
        }
        else if(type == "leave_group")
        {
            var id = $(this).parents("#menu_dialog").attr("data-user-id");
        }
        else {
            if(type == "public") var id = informBlock['id'];
            else var id = 0;
        }

        var data = {
            "type":type,
            "public_id":id
        };
        $.post( "/ajax/confirmDelete",data, function(data){
            if(type == "clear_message")
            {
                if(active_dialog == id)
                {
                    loadDialog(user_id,dialog_type,id);
                    $("#td_input .confirm_delete").fadeOut();
                }
                if(nav == "dialogs") $(".dialog[dialog_id='"+id+"'] .dialog_info p").html("Диалог пуст");
            }
            else window.location.reload();
        });
    });
    $("body").on("click","#group_switch input",function(){
        var id = $(this).attr("data-id-param");
        var el = $(this);
        $.ajax({
            url: '/ajax/updateSet',
            data: {
                "id_param":id
            },
            type: 'POST',
            success: function (data) {
                if(data == "false")
                {
                    if(el.is(":not(:checked)")) el.prop("checked",true);
                    else el.prop("checked",false);
                    /*$("#group_switch input[data-id-param='"+id+"']:checked")
                    if($(this).attr("checked") == "checked") $(this).removeAttr("checked");
                    else $(this).prop("checked","checked");*/
                    
                }
            }
        });
    });
    $("body").on("click",".create",function(){
        $("#column_list .inset_cl").html("");
        $("#column_list .loading").css("display","block");
        var page = $(this).attr("data-page");

        $.post( "/ajax/formCreate",{page:page},function(data){
            $("#column_list .loading").css("display","none");
            $("#column_list .inset_cl").html(data);
            $("#layout_messenger nav ul li a").removeClass("nav_active");
            data_public['avatar'] = "none";
            pageCreate = page;

            $(".list_contact").niceScroll({
                cursorwidth:5,
                railalign:"right",
                cursorcolor:"#c3c8d7",
                cursorborderradius:"30px",
                cursorborder:"0px solid transparent",
                cursoropacitymin:0,
                railpadding:{left:-35}
            });
        });
    });
    $("body").on("click","#select_type_public",function(){
        var id = parseInt($(this).attr("data-type"));
        var min = $(this).attr("data-min");
        var max = $(this).attr("data-max");
        var exception = $(this).attr("data-exception");

        if(id == max) id=min;
        else {
            id++;
            if(id == exception)
            {
                if(id+1>max) id=min;
                else id++;
            } 
        }

        $(this).attr("data-type",id);
    });

    function errorCreateDialog(el,text){
        clearTimeout(timer);
        var el = $(el);
        var value = el.attr("data-value");
        el.find("p").html(text);
        el.find(".button_loading").hide();
        timer = setTimeout(function(){
            el.removeAttr("data-status");
            el.prop("disabled",false);
            el.find("p").html(value);
        }, 1000);
    }
    $("body").on("submit","#form_create",function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        data.push({name: "page", value: pageCreate});
        if(pageCreate == "setting_public")
        {
            data.push({name: "type", value: $("#select_type_public").attr("data-type")});
            data.push({name: "avatar", value: data_public['avatar']});
        }
        $("#d_create .button_loading").show();
        $("#d_create").prop("disabled",true);
        $.post("/ajax/createDialog", data,function(data){
            if(data.status == "success") {
                var link = "dialogs";
                if(pageCreate == "setting_public") {
                    link = "group";
                    if(data.invite == "sending") sendInvite(data.data);

                    array_dialog.push(data.dialog);
                    createUserDialog(my_id,data.dialog,2);
                    console.log("data.dialog - "+data.dialog);
                }
                else if(pageCreate == "setting_ld") {
                    //createUserDialog(data.client,data.id);
                    array_dialog.push(data.id);
                    load();
                    link = "dialogs";
                    console.log("ddddddd - "+data.id);
                }
                
                loadPage(link);
            }
            else errorCreateDialog("#d_create",data.type);
            
        },"json");
    });

    $("body").on("click",".create_dial",function(){
        var id = $(this).parents(".friend").attr("data-id");
        var data = {
            select_contact:id,
            "page":"setting_ld"
        }
        $.post("/ajax/createDialog", data,function(data){
            if(data.status == "success") {
                load();
                //createUserDialog(id,data.id);

                create_dialog = data.id;
                loadPage("dialogs");
                
                array_dialog.push(data.id);
                console.log("ИД созданного диалога - "+data.id);
            }
        },"json");
    });
    $("body").on("click","#user_group #view_params",function(){
        if (!$(this).is(':checked')) {
            params_status = false;
            $type = $("#user_group #select_type_public").attr("data-type");
            $param = $("#user_group #select_type_public").attr("data-spec");
            if($type == 0) {
                params_search['type'] = "none";
                params_search['param'] = "none";
            }
            else {
                params_search['type'] = $type;
                if($type == 2) params_search['param'] = $param;
                else params_search['param'] = "none";
            }
            expandSearch("default");
            search($('.search').val());
        }
        else {
            params_status = true;
            expandSearch("expand");
        }
    });
    $("body").on("click","#user_list #view_params",function(){
        if (!$(this).is(':checked')) saveParamSearch();
    });

    $("body").on("focus",".shell_search .search",function(){
        expandSearch("expand");
    });
    $("body").on("focusout",".shell_search .search",function(){
        expandSearch("default");
    });

    function expandSearch(param){
        if(param == "expand")
        {
            $(".shell_search").css("width","100%");
            $(".create").hide();
        }
        else if(param == "default" && !params_status)
        {       
            if(!params_hover)
            {
                $(".shell_search").css("width","185px");
                $(".create").show();
            }
        }
    }
    $("body").on('mouseenter', 'label[for="view_params"]', function() {
        params_hover = true;
    });
    $("body").on('mouseleave', 'label[for="view_params"]', function() {
        params_hover = false;
        expandSearch("default");
    });

    $("body").on("click","#view_all",function(e){
        e.preventDefault();
        var value = $(this).attr("data-value");
        $("#column_list .error_column").css("display","none");
        $("#column_list .loading").css("display","block");
        $("#column_list .scroll_content").html("");

        $.post( "/ajax/viewAll",{value:value}, function(data) {
            $("#column_list .loading").css("display","none");
            $("#column_list .scroll_content").html(data);
            $("#view_params").prop("checked",false);
            $(".scroll_content").getNiceScroll().resize();
        });
    });

    $("body").on("keyup",".search",function(e) {
        search($(this).val());
    });
    function search(request)
    {
        if(!search) search = true;
        $("#column_list .error_column").css("display","none");
        $("#column_list .loading").css("display","block");
        $("#column_list .scroll_content").html("");
        $.ajax({
            url:"/ajax/search",
            method: "POST",
            data:{
                table:search_table,
                request:request,
                params:params_search
            },
            success: function(data){
                $("#column_list .loading").css("display","none");
                if(data == "fail") $("#column_list .no_search").css("display","block");
                else if(data == "no_data" ) $("#column_list .no_contact").css("display","block");
                else $("#column_list .scroll_content").html(data);
                $(".scroll_content").getNiceScroll().resize();
            }
        });
    }
    function saveParamSearch(){
        if(search_table == "students")
        {
            params_search['specialty'] = $("#select_spec").val();
            params_search['course'] = $("#select_curs").val();
            params_search['pol'] = $("input[name='select_pol']:checked").val();
        }
        search($('.search').val());
    }
    $("body").on("click","#clear_param",function(e) {
        if(search_table == "students")
        {
            $("#select_spec option[value='none']").prop("selected",true);
            $("#select_curs option[value='none']").prop("selected",true);
            $("#sp_1").prop("checked",true);
            $('#select_spec').niceSelect('update');
            $('#select_curs').niceSelect('update');
            $("#view_params").prop("checked",false);
        }
        saveParamSearch();
    });




    $("body").on("click","#invite",function(){
        $("#dynamic_layout").html("");
        $("#dynamic_layout,#group_page").css("display","none");
        $("#info_block .loading").css('display',"block");

        $.post( "/ajax/invite_list",{"public_id":informBlock['id']}, function(data) {
            $("#info_block .loading").css('display',"none");
            $("#dynamic_layout").html(data);
            $("#dynamic_layout,#group_page").css("display","block");
        });
    });

    $("body").on("click","#setting",function(){
        $("#dynamic_layout").html("");
        $("#dynamic_layout,#group_page").css("display","none");
        $("#info_block .loading").css('display',"block");
        var public = $(this).attr("data-public-id");
        data_public['avatar'] = "none";

        $.post( "/ajax/setting_group",{public:public}, function(data) {
            $("#info_block .loading").css('display',"none");
            $("#dynamic_layout").html(data);
            $("#dynamic_layout,#group_page").css("display","block");
            

            $(".list_contact").niceScroll({
                cursorwidth:5,
                railalign:"right",
                cursorcolor:"#c3c8d7",
                cursorborderradius:"0px",
                cursorborder:"0px solid transparent",
                cursoropacitymin:0,
                railpadding:{right:0}
            });
        });
    });

    $("body").on("click",".user_public button",function(){
        var user = $(this).parent(".user_public").attr("data-id");
        var public = $(this).parents("#admin_public").attr("data-public-id");

        $.post( "/ajax/excludeUserPublic",{"student":user,"public":public}, function(data) {
            $(".user_public[data-id='"+user+"']").remove();
            $("#count_sub span").html($("#count_sub span").html()-1);
        });
    });
    $("body").on("click","#edit_setting_public",function(){
        var public = $(this).parents("#admin_public").attr("data-public-id");
        var name = $("#admin_public #edit_name_public").val();
        var type = $("#admin_public #select_type_public").attr("data-type");

        var data = {
            "avatar":data_public['avatar'],
            "public":public,
            "name":name,
            "type":type
        };
        $("#edit_setting_public .button_loading").show();
        $("#edit_setting_public").prop("disabled",true);
        $.post( "/ajax/updatePublic",data, function(data) {
            if(nav=="group") loadPage("group");

            if(data.status == "fail") errorCreateDialog("#edit_setting_public",data.type);
            else errorCreateDialog("#edit_setting_public","Успех");
        }, "json");
    });
    $("body").on("click",".join",function(){
        actionInvite = true;
        var public_id = $(this).parents(".invite_public").attr("data-public");
        actionPublic(public_id);
        noticeInvite(2,1);
    });

    $("body").on("click",".invite_public .action_invite a",function(e){
        e.preventDefault();
        var public = $(this).parents(".invite_public").attr("data-public");

        $.post( "/ajax/deleteInvite",{"public":public}, function(data) {
            if(data=="success"){
                var el = $(".invite_public[data-public='"+public+"']");
                el.attr("data-anim","true");
                setTimeout(function(){
                    el.remove();
                    actionInvite = false;

                    noticeInvite(2,1);
                    if(notice_invite == 0) $(".invite_title").remove();

                }, 300);
            }
        });
    });
    $("body").on("click","#group_info",function(){
        actionPublic(informBlock['id']);
    });
    function actionPublic(public_id){
        $.post( "/ajax/actionPublic",{"public_id":public_id}, function(data) {
            console.log(data);
            if(data.status == "success")
            {
                if(data.type == "leave") {

                    if(nav == "dialogs") $(".dialog[dialog_id='"+data.dialog+"']").remove();
                    unreadMessage(2,data.unread);
                    hideDialog();
                    array_dialog.splice( $.inArray(data.id, array_dialog), 1 );
                    leaveUserDialog(my_id,data.dialog);
                }
                else if(data.type == "join"){
                    array_dialog.push(data.dialog);
                    createUserDialog(my_id,data.dialog,2);
                }
                if(!actionInvite && nav == "group") loadPage("group");
                if(nav == "group" && invite != 0 && actionInvite){
                    var el = $(".invite_public[data-public='"+public_id+"']");
                    el.attr("data-anim","true");
                    setTimeout(function(){
                        el.remove();
                        loadPage("group");
                        actionInvite = false;
                    }, 1000);
                }
                if(informBlock['type'] == "public") informLoad(informBlock['type'],informBlock['id']);
            }
        },"json");
    }
    $("body").on("click","#invite_group_contact",function(){
        var contact = {};
        contact = $.map( $("#invite_to_group input[type='checkbox']:checked"), function(el){ return $(el).val(); });
        if(!$.isEmptyObject(contact))
        {
            var data = {
                "public_id":informBlock['id'],
                "contact":contact
            };
            $("#invite_group_contact .button_loading").show();
            $("#invite_group_contact").prop("disabled",true);
            $.post( "/ajax/inviteContact",data, function(answer) {
                var text = "";
                if(answer.status == "success") {
                    text = "Приглашения разосланы";
                    sendInvite(answer.data);
                }
                else text = "Произошла ошибка";
                errorCreateDialog("#invite_group_contact",text);

                setTimeout(function(){
                    $('#group_page #dynamic_layout .close').focus().click();
                }, 1000);

            },"json");
        }
        else {
            $("#invite_group_contact").prop("disabled",true);
            errorCreateDialog("#invite_group_contact","Выберите контакты");
        }
    });
    //loadDialog(219);
    function hideDialog()
    {
        $(".insert_message").html("");
        $("#column_message .no_message").css("display","block");
        active_dialog = 0;
        active_dialog_type = 0;
        $("#input_chat").css("opacity","0");
    }
    function loadDialog(id,type,dialog_id){
        $(".chat_scroll").html("");
        $("#column_message .no_message").css("display","none");
        $("#column_message > .loading").css("display","block");
        loading_message = true;
        $.post( "/ajax/loadDialog",{"id":id,"type":type,"dialog_id":dialog_id}, function(data) {
            $("#page_chat").getNiceScroll().remove();
            $("#column_message > .loading").css("display","none");
            $(".insert_message").html(data);
            var div = $("#page_chat");
            div.scrollTop(div.prop('scrollHeight'));
            height_chat = $(".chat_scroll").height();
            $(".insert_message .no_message").css("display","block");
            $("#page_chat").niceScroll({
                cursorwidth:5,
                railalign:"right",
                cursorcolor:"#c3c8d7",
                cursorborder:"0px solid transparent",
                cursoropacitymin:0.5,
                railpadding:{right:-20}
            });
            switchRoom(dialog_id);
            active_dialog = dialog_id;
            active_dialog_type = type;

            var data_unread = parseInt($(".dialog[dialog_id='"+dialog_id+"'] .m_counter").attr("data-unread"));
            $(".dialog[dialog_id='"+dialog_id+"'] .m_counter").attr("data-unread",0);
            $('#input_chat textarea').focus();
            unreadMessage(2,data_unread);
            if(data_unread !=0) readMessages(dialog_id);
            $("#input_chat").css("opacity","1");
            loading_message = false;
        });
    }

    $("body").on("click",".dialog",function(){
        var id = $(this).attr('dialog_id');
        clickDialog(id);
    });
    function clickDialog(dialog){
        var el = $(".dialog[dialog_id='"+dialog+"']");

        $(".dialog").removeClass("select_dialog");
        el.addClass("select_dialog");
        var dialog_id = dialog;
        var type = el.attr('data-type');
        var id = el.attr('data-id');
        informLoad(type,id);
        loadDialog(id,type,dialog_id);
    }
    $("body").on("click",".open_dial",function(){
        var id = $(this).parents(".friend").attr("data-id");
        $.post( "/ajax/userDialogID",{"user_id":id}, function(data) {
            loadDialog(id,"profile",data);
        });
    });
    $("body").on("click","#menu li",function(){
        $("#menu").removeAttr("data-hover");
        setTimeout(function(){$("#menu").attr("data-hover","true");}, 500);
        var type = $(this).attr("data-type");
        var strong = "",text="";
        $("#menu_dialog .confirm_delete").attr("data-del",type);



        if(type == "clear_message") 
        {
            strong = "Вы действительно хотите очистить переписку?";
            text = "Вы не сможете восстановить сообщения после удаления!";
        }
        else if(type == "delete_dialog")
        {
            strong = "Вы действительно хотите удалить диалог?";
            text = "Вы не сможете восстановить сообщения после удаления диалога!";
        }
        else if(type == "leave_group")
        {
            strong = "Вы действительно хотите покинуть группу?";
            text = "После выхода из группы, диалог с ней будет удален. После вступления в группу, диалог восстановится!";
        }
        $("#menu_dialog .confirm_delete strong").html(strong);
        $("#menu_dialog .confirm_delete p").html(text);

        $("#menu_dialog .confirm_delete").css("display","block");
    });

    $("#stickers ul").getNiceScroll().hide();
    var timer_sticker = 0;
    $("#view_stickers,#stickers").hover(function(){
        clearTimeout(timer_sticker);
            $("#stickers").css("display","block");
            $("#stickers ul").getNiceScroll().show();
            $("#view_stickers").css("background-position","-23px 0");
            $("body").css("cursor","pointer");

            $("#stickers ul").niceScroll({
                cursorwidth:5,
                railalign:"right",
                cursorcolor:"#c3c8d7",
                cursorborderradius:"20px",
                cursorborder:"0px solid transparent",
                cursoropacitymin:0,
                railpadding:{left:10}
            });
    },function(){
        timer_sticker = setTimeout(function(){hideSticker();}, 100);
    });
    function hideSticker(){
        $("#stickers ul").getNiceScroll().hide();
        $("#stickers").css("display","none");
        $("#view_stickers").css("background-position","0 0");
        $("body").css("cursor","default");
    }

    $("body").on("click","#stickers li",function(){
        var sticker = $(this).attr("data-sticker-id");
        hideSticker();
        sendMessage(sticker,"sticker");
    });
    function unreadMessage(pointer,number){
        if(pointer == 1) unread_message+=number;
        else unread_message-=number;

        if(unread_message == 0) $("nav li:nth-child(1)").attr("data-notice",0);
        else $("nav li:nth-child(1)").attr("data-notice",1);
    }
    function noticeInvite(pointer,number){
        if(pointer == 1) notice_invite+=number;
        else notice_invite-=number;

        if(notice_invite == 0) $("nav li:nth-child(3)").attr("data-notice",0);
        else $("nav li:nth-child(3)").attr("data-notice",1);
    }







    //socket.io client
    //var array_dialog = array_dialog.split(",");
    function readMessages(dialog)
    {
        socket.emit('readMessages',dialog);
    }
    socket.on('readMessages', function(dialog) {
        if(parseInt(dialog) == parseInt(active_dialog)) $(".msg").removeClass("no_read");
    });
    var timer_status = 0;
    $("body").on("keyup","#input_chat textarea",function(e) {
        if(e.which != 13) status_edit();
    });
    function status_edit(){
        clearTimeout(timer_status);
        socket.emit('status_edit', my_id,active_dialog_type);
    }

    socket.on('status_edit', function (id,dialog,text) {
        clearTimeout(timer_status);
        if(id != my_id && dialog == active_dialog)
        {
            $("#status_edit p").html(text);
            $("#status_edit").fadeIn();
            timer_status = setTimeout(function(){
                $("#status_edit").fadeOut();
                console.log("5");
            }, 2000);
        }
    });

    function sendInvite(data){
        socket.emit('invite', data);
    }
    socket.on('sendInvite', function (data) {
        console.log(data.length);
        var coincidence = false;
        for(var i=0;i<data.length;i++)
        {
            if(data[i]['user'] == my_id)
            {
                coincidence = true;
                break;
            }
        }
        if(coincidence)
        {
            $.post( "/ajax/viewInviteOne",{"invite":data[i]['invite']}, function(data) {
                noticeInvite(1,1);
                playAudio("new_invite");
                if(nav == "group")
                {
                    var insert = "";
                    if(notice_invite == 1) {
                        insert = '<p class="invite_title">Приглашения <span>'+notice_invite+'</span></p>'+data;
                        $("#user_group .scroll_content").prepend(insert);
                    }
                    else {
                        $(".invite_title span").html(notice_invite);
                        $(".invite_title").after(data);
                    }
                    $(".no_group").css("display","none");
                }
            });
        }
    });

    function leaveUserDialog(id,dialog_id){
        socket.emit('leave_dialog', id,dialog_id);
    }
    socket.on('leave_dialog', function (user_id,dialog_id) {
        if(my_id == user_id) {
            socket.emit('leave', dialog_id);
        }
    });


    function createUserDialog(id,dialog_id,type){
        socket.emit('join_dialog', id,dialog_id,type);
    }
    socket.on('createDialog', function (user_id,dialog_id,type) {
        if(my_id == user_id) {
            socket.emit('create', dialog_id);
            $.post( "/ajax/viewDialogOne",{"dialog_id":dialog_id,"unread":0}, function(data) {
                if(data != "fail") {
                    if(nav == "dialogs")
                    {
                        $(".no_dialogs").remove();
                        $("#user_dialogs .scroll_content").prepend(data);
                    }
                    if(type == 1)
                    {
                        unreadMessage(1,1);
                        playAudio("new_message");
                    }
                }
            });
        }
    });
    function load()
    {
        var userid = Math.ceil(Math.random()*2);
        // при подключении к серверу запрашивайте имя пользователя с анонимным обратным вызовом
        socket.emit('load_dialog', my_id,array_dialog);
        console.log("test");
    }

    // слушатель, когда сервер передает 'обновление чата, обновляет тело чата
    socket.on('console', function (data) {
        console.log(data);
    });
    socket.on('updatechat', function (user_id, data, room,message,type) {
        console.log("Обновляем диалог: "+room);
        if(nav == "dialogs")
        {
            var last_message = message;
            if(type == "sticker") last_message = "Стикер";
            if(user_id == my_id) last_message = "<span>Вы: </span>"+last_message;
            $(".dialog[dialog_id='"+room+"'] .dialog_info p").html(last_message);
            $(".dialog[dialog_id='"+room+"']").parent().prepend($(".dialog[dialog_id='"+room+"']"));

            if(user_id == my_id) $("#user_dialogs .scroll_content").scrollTop(0);
        }
        if(active_dialog == room)
        {
            clearTimeout(timer_status);
            $("#status_edit").fadeOut();

            console.log("type: "+last_message);
            $.post( "/ajax/viewMessage",{"id":data,"dialog_id":room}, function(data) {
                if(data !="fail")
                {
                    var div = $("#page_chat");
                    $(".chat_scroll").append(data);
                    var destination = div.prop('scrollHeight');
                    $("#page_chat").getNiceScroll().onResize();
                    div.stop();
                    div.animate({ 
                            scrollTop: destination 
                        },{
                            duration:1000,
                            specialEasing: {
                            scrollTop: 'linear'
                        }
                    });
                    loadMessage++;
                    height_chat = $(".chat_scroll").height();
                    if(user_id != my_id) readMessages(room);
                    $(".no_message").css("display","none");
                }
            });
        }
        else {
            if(nav == "dialogs")
            {
                var unread = parseInt($(".dialog[dialog_id='"+room+"'] .m_counter").attr("data-unread"))+1;
                $(".dialog[dialog_id='"+room+"'] .m_counter").attr("data-unread",unread);
                $(".dialog[dialog_id='"+room+"'] .m_counter i").html(unread);
                playAudio("new_message");
                unreadMessage(1,1);
            }
            else {
                playAudio("new_message");
                unreadMessage(1,1);
            }
        }
    });
    function switchRoom(room){
        console.log("Переключаемся на диалог: "+room);
        socket.emit('switchRoom', room);
    }
    $('#send_message').click( function() {
        var message = $('#input_chat textarea').val();
        if(message != "")
        {
            $('#input_chat textarea').focus();
            $('#input_chat textarea').val("").css("height","30px");
            setCursorPosition($('#input_chat textarea'), 0, 0);
            sendMessage(message,"text");
        }
    });
    function sendMessage(message,type)
    {
        $.post( "/ajax/saveMessage",{"message":message,"dialog_id":active_dialog,"type":type}, function(data) {
            if(data.status != "fail") {
                if(data.id_user != "none") createUserDialog(data.id_user,active_dialog,1);
                socket.emit('sendchat', data.id_message, message,my_id,type);

                //console.log("data.id_user - "+data.id_user);
            }
        },"json");
    }

    // когда клиент нажимает ENTER на клавиатуре
    $('body').keypress(function(e) {
        if(!e.shiftKey && e.which === 13) {
            event.preventDefault();
            $(this).blur();
            $('#send_message').focus().click();
        }
    });
    $("#replace_room").click(function(){
        var room = $("#room_edit").val();
        switchRoom(room);
    });

    function setCursorPosition(oInput,oStart,oEnd) {
        if (oInput.setSelectionRange) {
            oInput.setSelectionRange(oStart,oEnd);
        } else if (oInput.createTextRange) {
            range = oInput.createTextRange();
            range.collapse(true);
            range.moveEnd('character', oEnd);
            range.moveStart('character',oStart);
            range.select();
        }
    }
    function playAudio(arg){
        var myAudio = new Audio;
        if(arg == "new_message") myAudio.src = "/sound/notification.mp3";
        else if(arg == "new_invite") myAudio.src = "/sound/invite.mp3";
        myAudio.play();
    }

    $("#view_stickers").click(function(){
        $("#no_message").remove();
    });
});