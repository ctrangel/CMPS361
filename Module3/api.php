<!doctype html>
<html>

<head>
    <title>API</title>
</head>

<body style="

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;

">
    <h1>Welcome to World Of Jars PHP edition</h1>
    <p>API for inventory</p>

    <div id="data-box" style="
    
        width: 800px;
        padding: 10px;
        margin: 10px;
        border: 10px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        font-family: Arial, sans-serif;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;

    
    
    ">


        <?php
        $url = 'http://localhost:8003/api/v1/inventory';

        $urlWithParams = $url . '?item=1';

        $session = curl_init();

        curl_setopt($session, CURLOPT_URL, $urlWithParams);

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($session);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($session);
        } else {
            // retrun JSON response
            $responseData = json_decode($response, true);

            echo json_encode($responseData, JSON_PRETTY_PRINT);

            echo $response;
        }

        curl_close($session);
        ?>

    </div>

</body>

</html>