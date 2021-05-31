const path = require('path');
const http = require('http');
const express = require('express');

const app = express();
const server = http.createServer(app);

const io = require('socket.io')(server);
  
app.use(express.static(path.join(__dirname, 'public')));

const PORT = 3001 || process.env.PORT;

server.listen(PORT, console.log(`Сервер запущен по порту ${PORT}`));