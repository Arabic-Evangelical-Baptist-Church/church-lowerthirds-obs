<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>إضافة اسم جديد</title>
    <link href="./styles/icomoon/style.css" rel="stylesheet" />
    <link href="./styles/stylesheet.css" rel="stylesheet" />
  </head>
  <body>
    <header>
      <h3>إضافة اسم جديد</h3>
    </header>
    <section>
      <form id="addNewLowerthirdForm">
        <div>
          <label for="nameInput">الاسم: </label>
          <input id="nameInput" name="nameInput" value="" placeholder="الاسم" />
        </div>
        <div>
          <label for="groupInput">المجموعة:</label>
          <input
            id="groupInput"
            name="groupInput"
            value=""
            placeholder="المجموعة"
          />
        </div>
        <button id="submitButton">
          تقديم
          <span class="icon-submit"></span>
        </button>
        <button>
          إعادة ضبط
          <span class="icon-spinner11"></span>
        </button>
      </form>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
      // Establish WebSocket connection
      const ws = new WebSocket("ws://localhost:<?php print $_ENV['WS_SERVER_PORT'] ?>");

      ws.onopen = function () {
        console.log("WebSocket connection established");
      };

      function addNewName() {
        const newName = prompt("Enter the new name:");
        const newGroup = prompt("Enter the group for the new name:");
        if (newName !== null && newGroup !== null) {
          fetch("http://localhost:<?php print $_ENV['EXPRESS_SERVER_PORT'] ?>/names", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              name: newName,
              group: newGroup,
            }),
          })
            .then((response) => {
              if (response.ok) {
                alert(`New name '${newName}' added to group '${newGroup}'`);
                location.reload(); // Refresh the page to reflect changes
              } else {
                alert("Failed to add new name");
              }
            })
            .catch((error) => console.error("Error adding new name:", error));
        }
      }

      document
        .getElementById("addNewLowerthirdForm")
        .addEventListener("click", function (e) {
          e.preventDefault();

          const newName = document.getElementById("nameInput").value;
          const newGroup = document.getElementById("groupInput").value;
          if (newName && newGroup) {
            fetch("http://localhost:<?php print $_ENV['EXPRESS_SERVER_PORT'] ?>/names", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                name: newName,
                group: newGroup,
              }),
            })
              .then((response) => {
                if (response.ok) {
                  alert(`New name '${newName}' added to group '${newGroup}'`);
                  location.reload(); // Refresh the page to reflect changes
                } else {
                  alert("Failed to add new name");
                }
              })
              .catch((error) => console.error("Error adding new name:", error));
          }
        });
    </script>
  </body>
</html>
