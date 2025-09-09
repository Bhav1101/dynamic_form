<?php
$conn = mysqli_connect("localhost", "root", "", "dynamic_forms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
