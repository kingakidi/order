<?php
    require_once("conn.php");
    $user_id = 3;
    $username = "sydee";
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

   
    function merchantDetailsById($id){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM users WHERE users.id = $id");
        if (!$uQuery) {
          die($uQuery->error);
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