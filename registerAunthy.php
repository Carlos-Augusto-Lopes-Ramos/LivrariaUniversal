<?php

    include_once("./DAO/Connection.php");
    include_once("./DAO/UserDTO.php");
    include_once("./Model/UserModel.php");
    include_once("./Controller/UserController.php");


if(isset($_POST['register'])){
    $admin = 0;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $secret = $_POST['secret'];
    if(isset($_POST['admin'])){
        $admin = 1;
    }
    $userDTO = new UserDTO($con);
    $userModel = new UserModel($userDTO);
    $userController = new UserController($userModel);
    $userController->register($name, $email, $password, $admin, $secret);
}
