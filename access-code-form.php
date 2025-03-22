<?php
include "db_conn.php"; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_code = trim($_POST['access_code']);
    $correct_code = "12345"; // Set your required code here

    if ($input_code === $correct_code) {
        // Grant access by setting a session variable
        $_SESSION['access_granted'] = true;
        header('Location: pages-register.php'); // Redirect to the protected page
        exit();
    } else {
        $error = "Invalid code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Code</title>
</head>
<style>
    body {
    background-color: #f4f4f4;
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    }
.code-container {  
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    border: 1px solid #999797;
    margin: 0 auto;
}
</style>
<body>
   
</html>