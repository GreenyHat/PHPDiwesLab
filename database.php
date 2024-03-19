<?php
$host = "localhost:8888"; //esto solo para MAMPS, en XAMPP, sería sólo localhost
$database = "contacts_app";
$user = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
  foreach ($conn->query("SHOW DATABASES") as $row) {
    print_r($row); //como la variable va a ser de dificil lectura, 
    //con el _r decimos que lo haga string para que sea readible para un humano
  }
  die();
} catch (PDOException $e) {
  die("PDO Connection Error: " . $e->getMessage());
}
