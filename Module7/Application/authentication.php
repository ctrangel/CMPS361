
<?php

//start session

session_start();

//database configuration

$host = 'localhost';

$db = 'WebAppDB';

$user = 'postgres';

$pass = 'col)Page94';

$port = '5432';

//Create connection to Postgres database

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");

//Check if the connection is successful

if (!$conn) {
    die("Connection failed: " . pg_last_error());

}

//Check if the form is submitted

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

        if(hash_equals($user[`password`], crypt($password, $user[`password`]))){
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
