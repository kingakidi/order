<?php
    require_once("conn.php");

    function clean($val){
        global $conn;
        return mysqli_real_escape_string($conn, strtolower(trim($val)));
    }
    if (isset($_POST['verifyUsername'])) {
        extract($_POST);
        $username = clean($username);
        $uQuery = $conn->query("SELECT * FROM users WHERE username='$username'"); 
        if (!$uQuery) {
            die($conn->error);
        }else{
            if ($uQuery->num_rows > 0) {
               $dbFullname = $uQuery->fetch_assoc()['fullname'];
               echo $dbFullname;
            }else{
                echo "Invalid Username";
            }
        }
    }