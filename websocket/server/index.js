const WebSocket = require('ws')

const wss = new WebSocket.Server({ port: 8082 })

wss.on("connection" , ws => {
    console.log('New Client Connected')

    ws.on("message" , data => {
        // Broadcast the message to all connected clients (tabs)
        wss.clients.forEach((client) => {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
            client.send(`${data}`);
            }
        });

    })


    ws.on("close", () => {
        console.log('Client left')
    })
})