var express = require('express')
  , app = express()
  , http = require('http')
  , server = http.createServer(app)
  , io = require('socket.io').listen(server);

server.listen(8008);

// routing
app.get('/', function (req, res) {
  res.sendfile(__dirname + '/index.html');
});

// имена пользователей, которые в данный момент подключены к чату
var usernames = {};

// номера, которые в настоящее время доступны в чате
//var rooms = ['room1','room2','room3'];

io.sockets.on('connection', function (socket) {
	
	// когда клиент выпускает 'adduser', это слушает и выполняет
	socket.on('invite', function(data){
		io.sockets.emit('sendInvite', data);
	});


	socket.on('leave_dialog', function(id,dialog_id){
		io.sockets.emit('leave_dialog', id,dialog_id);
	});
	socket.on('leave', function(dialog){
		socket.leave(dialog);
	});


	socket.on('readMessages', function(dialog){
		io.sockets.in(socket.room).emit('readMessages',dialog);
	});

	socket.on('status_edit', function(id,type){
		var text = "";
		if(type == "profile") text = "Набирает сообщение";
		else text = "Кто-то набирает сообщение";
		io.sockets.in(socket.room).emit('status_edit', id,socket.room,text);
	});

	socket.on('join_dialog', function(user_id,dialog_id,type){
		io.sockets.emit('createDialog', user_id,dialog_id,type);
	});
	socket.on('create', function(dialog_id){
		socket.join(dialog_id);
	});
	socket.on('load_dialog', function(username,dialogs){
		if(dialogs !== undefined)
		{
			// сохраните имя пользователя в сеансе сокета для этого клиента
			socket.username = username;
			// сохраните имя комнаты в сеансе сокета для этого клиента
			//socket.room = dialogs[0];
			// добавьте имя пользователя клиента в глобальный список
			usernames[username] = username;
			// отправить клиента в комнату 1
			for(var i=0;i<dialogs.length;i++) socket.join(dialogs[i]);
			// Эхо к клиенту они подключились
			// Эхо в комнату 1, что человек подключился к своей комнате

			//io.sockets.emit('console', dialogs);
			//socket.emit('updaterooms', socket.room, 'room1');
		}
	});
	
	// когда клиент выпускает 'sendchat', это слушает и выполняет
	socket.on('sendchat', function (data,message,id,type) {
		// мы говорим клиенту выполнить "обновить чат" с 2 параметрами
		io.sockets.in(socket.room).emit('updatechat',id, data,socket.room,message,type);
	});
	
	socket.on('switchRoom', function(newroom){
		//socket.leave(socket.room);
		socket.join(newroom);
		//socket.emit('updatechat', 'SERVER', 'вы подключились к '+ newroom,socket.room);
		// отправлено сообщение в старую комнату
		//socket.broadcast.to(socket.room).emit('updatechat', 'SERVER', socket.username+' покинул эту комнату',socket.room);
		// обновление заголовка комнаты сеанса сокета
		socket.room = newroom;

		//socket.broadcast.to(newroom).emit('updatechat', 'SERVER', socket.username+' присоединился к этой комнате',socket.room);
		//socket.emit('updaterooms', socket.room, newroom);
	});
	

	// когда пользователь отключается.. выполните это
	socket.on('disconnect', function(){
		// remove the username from global usernames list
		delete usernames[socket.username];
		// удалить имя пользователя из глобального списка имен пользователей
		io.sockets.emit('updateusers', usernames);
		// Эхо глобально, что этот клиент оставил
		//socket.broadcast.emit('updatechat', 'SERVER', socket.username + ' отключился');
		socket.leave(socket.room);
	});
});
