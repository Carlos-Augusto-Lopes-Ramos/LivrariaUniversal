<?php

    include_once("./DAO/Connection.php");
    include_once("./DAO/UserDTO.php");
    include_once("./Model/UserModel.php");
    include_once("./Controller/UserController.php");

    $userDTO = new UserDTO($con);
    $userModel = new UserModel($userDTO);
    $userController = new UserController($userModel);

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $secret = $_POST['secret'];
    $userController->login($email, $password, $secret);
}
if(isset($_POST['logout'])) {
    $userController->logout();
}