<?php

include_once '../DAO/Connection.php';
include_once '../DAO/CategoriesDTO.php';
include_once '../Model/CategoryModel.php';
include_once '../Controller/CategoryController.php';

$categoryDTO = new CategoriesDTO($con);
$categoryModel = new CategoryModel($categoryDTO);
$categoryController = new CategoryController($categoryModel);
if(isset($_POST['create'])){

    $name = $_POST['nome'];
    $categoryController->addCategory($name);
    header('Location: ./books.php');
    exit;
}
if(isset($_POST['link'])) {
    $categoria = $_POST['id_categoria'];
    $livro = $_POST['id_book'];
    $categoryController->linkCategory($livro, $categoria);
    header('Location: ./books.php');
}

if(isset($_POST['delete'])) {

}
