const express = require("express");
const fs = require("fs");
const app = express();
const port = process.env.EXPRESS_PORT || 3000;
const cors = require("cors");

app.use(express.json());
app.use(cors());

const fileName = "names.json";
// Load names from JSON file
let namesData = JSON.parse(fs.readFileSync(fileName));

// Function to save names to JSON file
function saveNamesToFile() {
	fs.writeFileSync(fileName, JSON.stringify(namesData, null, 2));
}

// GET endpoint to fetch all names
app.get("/names", (req, res) => {
	res.json(namesData);
});

// PUT endpoint to update a name by ID
app.put("/names", (req, res) => {
	const { id, name, group } = req.body;
	const index = namesData.findIndex((item) => item.id === id);
	if (index !== -1) {
		namesData[index] = { id, name, group };
		saveNamesToFile();
		res.sendStatus(200);
	} else {
		res.sendStatus(404); // Not found
	}
});

// DELETE endpoint to delete a name by ID
app.delete("/names", (req, res) => {
	const { id } = req.body;
	const index = namesData.findIndex((item) => item.id === id);
	if (index !== -1) {
		namesData.splice(index, 1);
		saveNamesToFile();
		res.sendStatus(200);
	} else {
		res.sendStatus(404); // Not found
	}
});

// POST endpoint to add a new name
app.post("/names", (req, res) => {
	const { name, group } = req.body;
	const id = Math.max(...namesData.map((item) => item.id), 0) + 1; // Generate a new unique ID
	namesData.push({ id, name, group });
	saveNamesToFile();
	res.sendStatus(201); // Created
});

app.listen(port, () => {
	console.log(`Express server listening at http://localhost:${port}`);
});
