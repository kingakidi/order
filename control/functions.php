<?php
    require_once("conn.php");
    
    function clean($val){
        global $conn;
        return mysqli_real_escape_string($conn, strtolower(trim($val)));
    }

    function error($val){
        return "<div class='text-danger'> $val </div>";
    };
      
    function success($val) {
        return "<div class='text-success'> $val </div>";
    };

    function verifyUsername($username){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM users WHERE username='$username'"); 
        if (!$uQuery) {
            die($conn->error);
        }else{
            if ($uQuery->num_rows > 0) {
                $dbFullname = $uQuery->fetch_assoc()['fullname'];
                return true;
            }else{
                return false;
            }
        }
    }
    function merchantDetailsById($id){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM users WHERE users.id = $id");
        if (!$uQuery) {
         return die($conn->error);
        }else{
          return $uQuery->fetch_assoc();
        }
    }

    function merchantDetailsByUsername($username){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM users WHERE users.username = $username");
        if (!$uQuery) {
         return die($conn->error);
        }else{
          return $uQuery->fetch_assoc();
        }
    }

    
    
    function getRequestDetailsById($id){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM request_table WHERE request_table.id = $id");
        if (!$uQuery) {
          die($uQuery->error);
        }else{
          return $uQuery->fetch_assoc();
        }
    }

    // USERNAME CHECK 
    function usernameCheck($val){
        global $conn; 

        $query = $conn->query("SELECT * FROM users WHERE users.username = '$val'");

        if (!$query) {
            die("Unable to verify username");
        }else{
            if($query->num_rows > 0){
                return true; 
            }else{
                return false; 
            }
        }
    }

    // EMAIL CHECK 
    function emailCheck($val){
        global $conn; 

        $query = $conn->query("SELECT * FROM users WHERE users.email = '$val'");

        if (!$query) {
            die("Unable to verify email address");
        }else{
            if($query->num_rows > 0){
                return true; 
            }else{
                return false; 
            }
        }
    }

    // PHONE NUMBER CHECK 
    function phoneCheck($val){
        global $conn; 

        $query = $conn->query("SELECT * FROM users WHERE users.phone = '$val'");

        if (!$query) {
            die("Unable to verify phone number");
        }else{
            if($query->num_rows > 0){
                return true; 
            }else{
                return false; 
            }
        }
    }