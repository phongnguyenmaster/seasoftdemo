var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

// Rom chat
io.on('connection', (socket) => {
    socket.on('disconnect', () => {
    });
  socket.on('newmessage', (msg) => {
    socket.broadcast.emit('MessagePosted', msg);
  });
});

// Private Chat
const privateChat = io.of('/privatechat');
privateChat.on('connection', socket => {
    console.log('someone connected private chat');
});
privateChat.emit('hi', 'everyone!');

http.listen(3000, function () {
    console.log('Listening on Port 3000');
});
