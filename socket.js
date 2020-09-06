var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

// Public Rom chat
io.on('connection', (socket) => {
    socket.on('disconnect', () => {
        console.log('Disconnect room');
    });
    socket.on('newmessage', (msg) => {
        socket.broadcast.emit('MessagePosted', msg);
    });
});

// Private Chat
// Cteate room chat for per 1-1 client.
const privateChat = io.of('/privatechat');
privateChat.on('connection', socket => {
    socket.on('register', function (privateKey) {
        socket.join(privateKey);
    });
    socket.on('unregisterregister', function (privateKey) {
        socket.leave(privateKey);
    });
    socket.on('private_chat', function (data) {
        console.log('MessagePosted' + data.privateKey);
        socket.to(data.privateKey).emit('MessagePosted', data);
    });
});
http.listen(3000, function () {
    console.log('Listening on Port 3000');
});
