<?php
include '../backend/config/db.php';
if($conn){
    echo "DB Connected!";
} else {
    echo "DB Error!";
}
?>
