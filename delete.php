<?php
session_start();
require "database.php"; //Matiz: esto no es un imput como tal, es traerse el codigo de ese archivo

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$id = $_GET["id"]; //Esto vuelve a tener peligro de sqlInjection ya que el paquete lo manda el cliente (recordemos que el cliente puede ser un programa, como con curl)

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
// $statement->bindParam(":id", $id); con la inclusion del array asociativo en execute le decimos que sustituya el parametro por el valor
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo ("HTTP 404 NOT FOUND"); //esto es para evitar que nos borren un id que no existe mediante curl GET o desde el propoio navegador:http://localhost/contacts-app/delete.php?id=32
  return; //Esto entiendo que es igual que poner un else y lo de abajo???
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo ("HTTP 403 UNAUTHORIZED");
  return;
}

$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);

$_SESSION["flash"] = ["message" => "Contact {$contact['name']} deleted."];

header("Location: home.php");//redirect
