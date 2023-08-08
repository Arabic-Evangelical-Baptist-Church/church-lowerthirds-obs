const WebSocket = require('ws')
const port = process.env.PORT || 3000
const wss = new WebSocket.Server({ port })

wss.on("connection" , ws => {
    console.log('New Client Connected')

    // Send a ping message to the client at regular intervals
    const pingInterval = setInterval(() => {
        if (ws.readyState === WebSocket.OPEN) {
        ws.ping();
        }
    }, 30000); // Send ping every 30 seconds

    // Listen for pong responses from the client
    ws.on("pong", () => {
        console.log("Received pong from client");
    });

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
        clearInterval(pingInterval);
        console.log('Client left')
    })
})
