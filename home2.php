<?php
     if (!$_SESSION['oUsername']) {
        header("Location: ./login.php");
    }