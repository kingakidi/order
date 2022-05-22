<?php
    include "control/conn.php";
    $_SESSION['oUsername'] = NULL;
   
    session_destroy();
    header("Location: ./login.php");