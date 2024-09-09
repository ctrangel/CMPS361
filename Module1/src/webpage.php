<html>

<head>
    <title>Web Webbing</title>
    <style>
        body {
            background-color: lightblue;
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            color: white;
            text-align: center;
        }

        p {
            font-family: verdana;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <h1>Welcome to my Web Page</h1>
    <p>
        <?php

        $name = "Christian";
        echo "Hello, $name!";
        echo
        " This is my first PHP script!";

        $string = "RandomName";
        $int = 123;

        echo "The string is $string and the integer is $int";

        ?>
    </p>
</body>

</html>