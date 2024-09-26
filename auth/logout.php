<?php
     session_start();
     session_destroy();
     header("Location: /telad/auth/login.php");
     exit();
    