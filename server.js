const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
var Redis = require('ioredis');
var redis = new Redis({
  host: '127.0.0.1', // Redis server host
  port: 6379,        // Redis server port
});
var users = [];
var groups = [];

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

redis.subscribe('private-channel', function () {
  console.log('subscribed to private channel');
});

// redis.subscribe('group-channel', function() {
//   console.log('subscribed to group channel');
// });


redis.on('message', function (channel, message) {
  message = JSON.parse(message);
  console.log('1234567890');
  console.log(message);
});

io.on('connection', function (socket) {
  socket.on("user_connected", function (user_id) {
    users[user_id] = socket.id;
    io.emit('updateUserStatus', users);
    console.log("user connected " + user_id);
  });

  socket.on('disconnect', function () {
    var i = users.indexOf(socket.id);
    users.splice(i, 1, 0);
    io.emit('updateUserStatus', users);
    console.log(users);
  });
});
