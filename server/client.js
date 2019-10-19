
	var array_dialogs = [5,8];
	var userid = Math.ceil(Math.random()*2);
	// при подключении к серверу запрашивайте имя пользователя с анонимным обратным вызовом
	socket.on('connect', function(){
		// вызовите серверную функцию 'adduser' и отправьте один параметр (значение prompt)
		socket.emit('load_dialog', userid,array_dialogs);
	});

	// слушатель, когда сервер передает 'обновление чата, обновляет тело чата
	socket.on('updatechat', function (username, data, room) {
		if(active_dialog != 0)
		{
			if(active_dialog == room) $('.chat_scroll').append('<b>(комната - '+room+')'+username + ':</b> ' + data + '<br>');
			else {
				var unread = parseInt($("a[data-dialog='"+room+"']").attr("data-unread"))+1;
				$("a[data-dialog='"+room+"']").attr("data-unread",unread);
				$("a[data-dialog='"+room+"'] span").html(unread);
			}
		}
	});

	function switchRoom(room){
		socket.emit('switchRoom', room);
	}
	
	// при загрузке страницы
	$(function(){
		// когда клиент нажимает кнопку Отправить
		$('#send_message').click( function() {
			var message = $('#input_chat textarea').val();
			$('#data').val('');
			// скажите серверу выполнить 'отправить чат' и отправить по одному параметру
			var dt = parseInt(new Date().getTime()/1000);
			console.log(dt);
			socket.emit('sendchat', message);
		});

		// когда клиент нажимает ENTER на клавиатуре
		$('#data').keypress(function(e) {
			if(e.which == 13) {
				$(this).blur();
				$('#datasend').focus().click();
			}
		});
		$("#replace_room").click(function(){
			var room = $("#room_edit").val();
			switchRoom(room);
		});
		$("a").click(function(){
			var dialog_id = $(this).attr("data-dialog");
			switchRoom(dialog_id);
			active_dialog = dialog_id;
			$(this).attr("data-unread",0);
		});
	});