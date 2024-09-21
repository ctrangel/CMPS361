const express = require("express");
const { Pool } = require("pg");

const app = express();
const port = 3000;

// PostgreSQL connection setup
const pool = new Pool({
  user: "ctrangel",
  host: "localhost",
  database: "WebAppDB",
  password: "Rangel155",
  port: 5432,
});

// Middleware to parse JSON
app.use(express.json());

//####################### ROUTES #######################

// default route
app.get("/", (req, res) => {
  res.send("Welcome to the Water API!");
});

// Get all waters
app.get("/waters", async (req, res) => {
  try {
    const result = await pool.query("SELECT * FROM water");
    res.json(result.rows);
  } catch (err) {
    console.error(err);
    res.status(500).send("Server Error");
  }
});

// Get water entries by type
app.get('/waters/:type', async (req, res) => {
    const { type } = req.params;
    try {
        const result = await pool.query('SELECT * FROM water WHERE type = $1', [type]);
        if (result.rows.length === 0) {
            return res.status(404).send('Water not found');
        }
        res.json(result.rows);
    } catch (err) {
        console.error(err);
        res.status(500).send('Server Error');
    }
});

// Add a new water entry
app.post('/waters', async (req, res) => {
    const { brand, type, price } = req.body;
    try {
        const result = await pool.query(
            'INSERT INTO water (brand, type, price) VALUES ($1, $2, $3) RETURNING *',
            [brand, type, price]
        );
        res.status(201).json(result.rows[0]);
    } catch (err) {
        console.error(err);
        res.status(500).send('Server Error');
    }
});

// Update a water entry by id
app.put('/waters/:id', async (req, res) => {
    const { id } = req.params;
    const { brand, type, price } = req.body;
    try {
        const result = await pool.query(
            'UPDATE water SET brand = $1, type = $2, price = $3 WHERE id = $4 RETURNING *',
            [brand, type, price, id]
        );

        if (result.rows.length === 0) {
            return res.status(404).send('Water not found');
        }
        res.json(result.rows[0]);
    } catch (err) {
        console.error(err);
        res.status(500).send('Server Error');
    }
});

// Delete a water entry by id
app.delete('/waters/:id', async (req, res) => {
    const { id } = req.params;
    try {
        const result = await pool.query('DELETE FROM water WHERE id = $1 RETURNING *', [id]);

        if (result.rows.length === 0) {
            return res.status(404).send('Water not found');
        }
        res.status(204).send(); // No content to send back
    } catch (err) {
        console.error(err);
        res.status(500).send('Server Error');
    }
});





// Start the server
app.listen(port, () => {
  console.log(`API running at http://localhost:${port}`);
});
