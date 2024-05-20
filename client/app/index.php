<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lower Third Names</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;800&display=swap"
      rel="stylesheet"
    />
    <link href="./styles/index.css" rel="stylesheet" />
  </head>
  <body>
    <div id="container">
      <div id="lowerthird-container">
        <div id="logo-container">
          <img src="./images/church-logo.png" alt="Logo source" />
        </div>
        <div id="small-name-container">فريق التسبيح</div>
        <div id="large-name-container">
          <span id="large-name">أمجد فرج</span>
        </div>
      </div>
    </div>
    <script>
      const ws = new WebSocket(
        "ws://localhost:<?php print $_ENV['WS_SERVER_PORT'] ?>"
      );
      let isDisplayed = false;
      let current_instance = "";
      ws.addEventListener("open", () => {
        console.log("We are now listening");
      });

      ws.addEventListener("message", (e) => {
        const data = JSON.parse(e.data);
        console.log(data);

        if (data.context === current_instance && isDisplayed) {
          // Right Button Clicked, hide the name
          document
            .getElementById("small-name-container")
            .classList.remove("open");

          setTimeout(() => {
            document.getElementById("large-name-container").style.width =
              0 + "px";
            document.getElementById("large-name-container").style.paddingRight =
              0 + "px";
          }, 400);

          setTimeout(() => {
            document.getElementById("logo-container").style.opacity = 0;
          }, 900);

          current_instance = "";
          isDisplayed = false;
        } else if (!isDisplayed || current_instance === "") {
          // New Request to show new name
          current_instance = data.context;
          isDisplayed = true;
          document.getElementById("logo-container").style.opacity = 1;
          updateInformation(data.name, data.group);
          setTimeout(() => {
            document
              .getElementById("small-name-container")
              .classList.add("open");
          }, 400);

          setTimeout(() => {
            document.getElementById("large-name-container").style.width =
              855 + "px";
            document.getElementById("large-name-container").style.paddingRight =
              120 + "px";
          }, 200);
        }
      });

      ws.addEventListener("ping", () => {
        ws.pong();
      });

      function updateInformation(name, group) {
        console.log(name, group);
        const praiserNameContainer = document.getElementById("large-name");
        praiserNameContainer.textContent = name;

        const praiserGroupContainer = document.getElementById(
          "small-name-container"
        );
        praiserGroupContainer.textContent = group;
      }
    </script>
  </body>
</html>
