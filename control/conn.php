<?php

    $conn = new mysqli("localhost", "root", "", "order"); 

    if (!$conn) {
        die($conn->error);
    }

    $flutterLiveSecretKey = "FLWSECK-605b4bafe429ef60e6c01d8ef4a13c53-X";