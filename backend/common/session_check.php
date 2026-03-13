<?php
if(!isset($_SESSION)) { session_start(); }

function check_user_session(){
    if(!isset($_SESSION['user_id'])){
        header("Location: ../frontend/login.php");
        exit;
    }
}
