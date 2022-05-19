<?php

    $conn = new mysqli("localhost", "root", "", "order"); 

    if (!$conn) {
        die($conn->error);
    }