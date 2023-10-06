From node:latest

WORKDIR /app

RUN apt update

COPY ./package*.json ./

RUN npm install

COPY ./server.js .

EXPOSE 5000

CMD [ "node", "server.js" ]