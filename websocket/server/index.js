const WebSocket = require('ws')

const wss = new WebSocket.Server({ port: 8082 })

const serviceInformation = {
    praiser: {
        name: '',
        group: 'فريق التسبيح'
    },
    preacher: {
        name: 'القس خالد غبريال',
        group: 'الكنيسة العربية المعمدانية ببوسطن'
    }
}

wss.on("connection" , ws => {
    console.log('New Client Connected')

    // Send the saved information by default here
    ws.send(JSON.stringify({
        type: 'recent-service-information',
        data: serviceInformation
    }))


    ws.on("message" , data => {

        const message = {}

        // Lets save the data here
        const json = JSON.parse(data)

        if(json.requestType === 'update-information'){
            serviceInformation.praiser.name = json.praiserName
            serviceInformation.praiser.group = json.praiserGroupTitle
            serviceInformation.preacher.name = json.preacherName
            serviceInformation.preacher.group = json.preacherGroupTitle
            console.log('Updated the information')
            return
        }

        message.type = json.requestType



        if(json.requestType === 'show'){
            if(json.viewType === 'praiser'){
                message.name = serviceInformation.praiser.name
                message.group = serviceInformation.praiser.group
            }else if (json.viewType === 'preacher'){
                message.name = serviceInformation.preacher.name
                message.group = serviceInformation.preacher.group
            }
        }else if(json.requestType === 'get-recent-information'){
            message.type = 'return-recent-information'
            message.data = serviceInformation
        }


        console.log(`Json recieved is ${JSON.stringify(json)}`)
        console.log(`Message sent is ${JSON.stringify(message)}`)

        // Broadcast the message to all connected clients (tabs)
        wss.clients.forEach((client) => {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
            client.send(`${JSON.stringify(message)}`);
            }
        });

    })


    ws.on("close", () => {
        console.log('Client left')
    })
})
