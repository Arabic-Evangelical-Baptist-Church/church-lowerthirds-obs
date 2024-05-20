<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>الأسماء المتاحة</title>
    <link href="./styles/icomoon/style.css" rel="stylesheet" />
    <link href="./styles/stylesheet.css" rel="stylesheet" />
  </head>
  <body>
    <header>
      <h3>
        الأسماء المتاحة
        <button onclick="window.location.reload();">
          تحديث النافذة
          <span class="icon-spinner11"></span>
        </button>
      </h3>
    </header>

    <section>
      <ul id="current-names"></ul>
    </section>
    <script>
      // Establish WebSocket connection
      const ws = new WebSocket("ws://localhost:<?php print $_ENV['WS_SERVER_PORT'] ?>");

      ws.onopen = function () {
        console.log("WebSocket connection established");
      };

      // Function to create buttons for each name
      function createButtons(li, person) {
        const buttonDiv = document.createElement("div");
        buttonDiv.classList.add("buttons-div");

        const toggleButton = createSingleButton(
          person,
          "تبديل",
          "icon-play3",
          "toggle-button",
          toggleFunc
        );

        const editButton = createSingleButton(
          person,
          "تحديث",
          "icon-pencil",
          "edit-button",
          editFunc
        );

        const deleteButton = createSingleButton(
          person,
          "حذف",
          "icon-bin",
          "delete-button",
          deleteFunc
        );

        li.appendChild(document.createTextNode(person.name + " "));
        buttonDiv.appendChild(toggleButton);
        buttonDiv.appendChild(editButton);
        buttonDiv.appendChild(deleteButton);
        li.appendChild(buttonDiv);
      }

      const createSingleButton = (
        person,
        title,
        iconClass,
        buttonClass,
        callbackFunc
      ) => {
        const button = document.createElement("button");

        const buttonIcon = document.createElement("span");
        buttonIcon.setAttribute("class", iconClass);
        button.appendChild(buttonIcon);
        button.setAttribute("aria-label", title);
        button.setAttribute("title", title);
        button.setAttribute("class", buttonClass);
        button.onclick = () => callbackFunc(person);

        return button;
      };
      // Fetch names from Express server
      fetch("http://localhost:<?php print $_ENV['EXPRESS_SERVER_PORT'] ?>/names")
        .then((response) => response.json())
        .then((names) => {
          const currentNames = document.getElementById("current-names");
          names.forEach((name) => {
            const li = document.createElement("li");
            createButtons(li, name); // Add buttons for each name
            currentNames.appendChild(li);
          });
        })
        .catch((error) =>
          console.error("Error fetching names from Express server:", error)
        );

      // Function to send message to WebSocket server to show a name
      function toggleFunc(person) {
        ws.send(JSON.stringify(person));
      }

      // Function to update the name on the Express server
      function editFunc(person) {
        const { name, id } = person;

        const newName = prompt(`Enter new name for ${name}:`, name);
        const newGroup = prompt(`Enter new group for ${name}:`, person.group);
        if (newName !== null && newGroup !== null) {
          fetch("http://localhost:<?php print $_ENV['EXPRESS_SERVER_PORT'] ?>/names", {
            method: "PUT",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              id: id,
              name: newName,
              group: newGroup,
            }),
          })
            .then((response) => {
              if (response.ok) {
                alert(
                  `Name ${name} updated to ${newName} (Group: ${newGroup})`
                );
                location.reload(); // Refresh the page to reflect changes
              } else {
                alert("Failed to update name");
              }
            })
            .catch((error) => console.error("Error updating name:", error));
        }
      }

      // Function to delete the name from the Express server
      function deleteFunc(person) {
        const { name, id } = person;
        if (confirm(`Are you sure you want to delete ${name}?`)) {
          fetch("http://localhost:<?php print $_ENV['EXPRESS_SERVER_PORT'] ?>/names", {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: id }),
          })
            .then((response) => {
              if (response.ok) {
                alert(`Name ${name} deleted`);
                location.reload(); // Refresh the page to reflect changes
              } else {
                alert("Failed to delete name");
              }
            })
            .catch((error) => console.error("Error deleting name:", error));
        }
      }
    </script>
  </body>
</html>
