<?php
/**
 * Example Application
 *
 * @package Example-application
 */
require_once "../configuration/config.php";
require_once "../configuration/dbconfig.php";
session_start();
include "search_page.php";
if (isset ($_SESSION['User'])){
    header("Location: index.php");
}
if(!isset($_POST['first_name'])) {
    $smarty->display('signup.tpl');
    exit();
}
$first_name = $last_name = $email = $password = $confirm = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = clean_input($_POST["first_name"]);
  $last_name = clean_input($_POST["last_name"]);
  $email = clean_input($_POST["email"]);
  $password = clean_input($_POST["password"]);
  $confirm = clean_input($_POST["confirm"]);
  $hash = crypt($password);
} 
  if ($password != $confirm){
  
  $message = "Please make sure your confirmation password matches your password";
  $smarty->assign('first_name', $first_name);
  $smarty->assign('last_name', $last_name);
  $smarty->assign('email', $email);
  $smarty->assign('msg', $message);
  $smarty->display('signup.tpl');
  exit();
}
  
  $sql = "SELECT *FROM user 
              WHERE Email = :email;";
  
  
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $rows = $stmt->fetchAll();
  $rowCount = count($rows);
     
        
    if ($rowCount == 1) {
         $message = "Email already registered \n 
                      Please use a different email address";
         $smarty->assign('msg', $message);
         $smarty->display('signup.tpl');
         exit();
    }else {
          $sql = "INSERT INTO user
                  (Email, `Password`, First_Name, Last_Name)
                  VALUES
                  (:e, :p, :f, :l)";
               
        }
    
    function clean_input($data) {
      $data = trim($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':e', $email);
$stmt->bindParam(':p', $hash);
$stmt->bindParam(':f', $first_name);
$stmt->bindParam(':l', $last_name);
$stmt->execute();
header("Location: index.php");
