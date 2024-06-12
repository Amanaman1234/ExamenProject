<?php
$servername= "localhost";
$username= "root";
$password="";
$dbname="examenvoedselbank";

$conn = mysqli_connect($servername,$username, $password, $dbname );

If(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}