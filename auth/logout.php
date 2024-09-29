<?php
     session_start();
     session_destroy();
     header("Location: /crm/auth/login.php");
     exit();
    