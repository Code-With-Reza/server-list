const express = require('express');
const http = require('http');
const socketIO = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIO(server);
const IP = process.argv[2] || "tnsmp.aternos.me";
const PORTS = process.argv[3] || 19132;

const bedrock = require('bedrock-protocol')
const mcClient = bedrock.createClient({
  //host: 'tnsmp.aternos.me',
  //port: 15558,      // optional, port to bind to, default 19132
  host: IP,
  port: PORTS,
  offline: false
})

// Express endpoint to serve HTML page
app.get('/', (req, res) => {
  res.sendFile(__dirname + '/index.html');
});

// Socket.io connection event
io.on('connection', (socket) => {
  console.log('A user connected');

  // Listen for messages from the web client
  socket.on('chat message', (msg) => {
    // Send the message to Minecraft
    mcClient.queue('text', {
      type: 'chat',
      needs_translation: false,
      source_name: mcClient.username,
      xuid: '',
      platform_chat_id: '',
      message: msg,
    });
  });

  // Listen for text packets from Minecraft and emit them to the web client
  mcClient.on('text', (packet) => {
    socket.emit('chat message', `<${packet.source_name}> ${packet.message}`);
  });

  // Disconnect event
  socket.on('disconnect', () => {
    console.log('User disconnected');
  });
});

// Start the server
const PORT = process.env.PORT || 8080;
server.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);

});
