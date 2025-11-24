<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guinness World Records 2025</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#00A9A5',
            'primary-hover': '#007E79',
            'primary-light': '#ffffff',
            'text-color': '#333333',
            'background-color': '#f8f9fa',
            'cart-red': '#FF3D57'
          }
        }
      }
    }
  </script>
</head>

<body class="bg-background-color font-sans">
<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
  <?php
  include("./component/header.php");
  ?>
  
  <div class="container mx-auto px-5 py-5">
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
      <!-- Imagem do Livro -->
      <div class="flex flex-wrap justify-center gap-5 p-5">
        <?php

        include_once("./DAO/Connection.php");
        include_once("./DAO/BookDTO.php");
        include_once("./Model/BookModel.php");
        include_once("./Controller/BookController.php");
        include_once("./DAO/UserDTO.php");
        include_once("./Model/UserModel.php");
        include_once("./Controller/UserController.php");

        $userDTO = new UserDTO($con);
        $userModel = new UserModel($userDTO);
        $userController = new UserController($userModel);

        $bookDTO = new BookDTO($con);
        $bookModel = new BookModel($bookDTO);
        $bookController = new BookController($bookModel);


        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
          $user = $userController->getIdByEmail($_SESSION['email']);
          $books = null;
          if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $books = $bookController->getAllBooksByUserIdLikeTitle($user, $search);
          } else {
            $books = $bookController->getAllBooksByUserId($user);
          }
          foreach ($books as $book) {
            echo '<div class="border border-gray-300 rounded-lg p-4 bg-white w-48 min-h-96 text-center shadow-lg hover:scale-105 transition-transform duration-300 flex flex-col justify-between">
                    <img src="./assets/images/' . $book['image'] . '" alt="' . $book['title'] . '" class="w-full h-auto max-h-72 object-cover rounded mb-2">
                    <h3 class="text-lg text-blue-800 my-2">' . $book['title'] . '</h3>
                    <p class="my-1 text-sm">📚 Autor: ' . $book['author'] . '</p>
                    <p class="my-1 text-sm">💲 Preço: R$ ' . $book['price'] . '</p>
                    <a href="./livro.php?id=' . $book['id'] . '" class="inline-block px-4 py-2 bg-gray-600 text-white rounded-full font-bold hover:bg-white hover:text-gray-600 transition-colors duration-300 text-center self-center mt-auto">Ver Detalhes</a>
                  </div>';
          }
        } else {
          echo '<div class="text-center py-12">
                  <h1 class="text-3xl font-bold text-gray-800 mb-4">Você precisa estar logado para acessar essa página.</h1>
                  <a href="./login.php" class="bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-primary-hover transition-colors duration-300">Fazer Login</a>
                </div>';
        }

        ?>
      </div>
    </div>
  </div>
</body>

</html>