<php>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebApplication</title>
    <link rel="stylesheet" href="styles.css">
    <script src="authentication.php"></script>
</head>
<body>
    <h2>Login Functionality</h2>

    <form class="login-form" label="login" action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <!-- add php submit -->
        <input type="submit" value="Submit">

    </form>

    <div class="error-message">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Get the username and password from the form
            $username = $_POST['username'];
            $password = $_POST['password'];

            //Query to check if the user exists in the database
            $query = "SELECT * FROM users WHERE username = $1 AND password = $2";

            //Prepare the query
            $result = pg_prepare($conn, "login_query", $query);

            //Execute the query
            $result = pg_execute($conn, "login_query", array($username, $password));

            //Check if the user exists
            if (pg_num_rows($result) > 0) {
                if (hash_equals($user[`password`], crypt($password, $user[`password`]))) {
                    //Set the session variables
                    $_SESSION['username'] = $username;
                    //Redirect to the home page
                    header('Location: home.php');
                } else {
                    echo "Invalid username or password";
                }
            } else {
                echo "YOU DO NOT EXIST";
            }
            //Close the connection
            pg_close($conn);
        }
        ?>
    
</body>
</html>

</php>