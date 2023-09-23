const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
// var Redis = require('ioredis');
// var redis = new Redis();
var users = [];
// var groups = [];

const app = express();
const server = http.createServer(app);

// Enable CORS for your Laravel application's origin
app.use(cors({
    origin: '*', // Replace with the actual URL of your Laravel app
    methods: ['GET', 'POST'],
    credentials: true,
}));

const io = socketIo(server, {
  cors: {
    origin: "*", // Replace with the actual URL of your frontend
    methods: ["GET", "POST"],
    credentials: true
  }
});

// ... Rest of your Socket.io server code ...

server.listen(8005, () => {
    console.log('Listening to port 8005');
});

io.on('connection', function (socket) {
    socket.on("user_connected", function (user_id) {
        users[user_id] = socket.id;
        io.emit('updateUserStatus', users);
        console.log("user connected " + user_id);
    });

    socket.on('disconnect', function() {
      var i = users.indexOf(socket.id);
      users.splice(i, 1, 0);
      io.emit('updateUserStatus', users);
      console.log(users);
  });
});
