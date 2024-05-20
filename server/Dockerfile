From node:latest

WORKDIR /app

RUN apt update

COPY ./app/package*.json ./

RUN npm install

COPY ./app .

EXPOSE 5000
EXPOSE 3000

CMD [ "npm", "start" ]