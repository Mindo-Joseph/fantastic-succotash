const express = require('express');

const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});

io.on('connection', (socket) => {
    socket.join(socket.handshake.query.user_id)
    console.log(socket.handshake.query.user_id+' user connected');

    socket.on('sendChatToServer', (message) => {
        console.log(message);
        io.sockets.emit('sendChatToClient', message);
    });

    socket.on('createOrder', (orderData) => {
        console.log("order created");
        console.log(orderData);
        (orderData.user_vendor).forEach(element => {
            io.sockets.emit('createOrderByCustomer_'+socket.handshake.query.subdomain+"_"+element.user_id, orderData);
        });
        (orderData.admins).forEach(element => {
            io.sockets.emit('createOrderByCustomer_'+socket.handshake.query.subdomain+"_"+element.id, orderData);
        });
        // io.sockets.emit('createOrderByCustomer', orderData);
    });
    
    socket.on('disconnect', () => {
        console.log(socket.handshake.query.user_id+' user disconnected');
    });
})
server.listen(3100, () => {
    console.log('Server is running');
})