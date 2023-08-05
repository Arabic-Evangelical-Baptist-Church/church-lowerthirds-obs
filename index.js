const WebSocket = require('ws')
const port = process.env.PORT || 3000
const wss = new WebSocket.Server({ port })

wss.on("connection" , ws => {
    console.log('New Client Connected')

    ws.on("message" , data => {
        // Broadcast the message to all connected clients (tabs)
        console.log(`Data Recieved is ${data}`)
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
