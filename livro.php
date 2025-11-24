<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Livro - Livraria Universal</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
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
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans min-h-screen">
    
    <div class="container mx-auto px-4 py-8">
        <?php include_once './component/header.php'; ?>
        
        <div class="max-w-6xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="index.php" class="hover:text-primary transition-colors">Início</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><span class="text-gray-800 font-medium">Detalhes do Livro</span></li>
                </ol>
            </nav>

            <!-- Book Details Card -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Book Image Section -->
                    <div class="lg:w-1/3 p-8">
                        <div class="relative">
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

                            if (isset($_GET['id'])) {
                                $book = $bookController->getBookById($_GET['id']);
                                if (!$book) {
                                    echo '<div class="text-center py-12">
                                            <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Livro não encontrado</h3>
                                            <p class="text-gray-600 mb-4">O livro que você está procurando não existe.</p>
                                            <a href="index.php" class="bg-primary text-white px-6 py-3 rounded-xl hover:bg-primary-hover transition-colors">
                                                Voltar ao início
                                            </a>
                                          </div>';
                                    exit;
                                }
                                echo '<div class="relative group">
                                        <img src="./assets/images/' . $book['image'] . '" class="w-full h-96 object-cover rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300" alt="' . $book['title'] . '">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 rounded-2xl transition-all duration-300"></div>
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Book Content Section -->
                    <div class="lg:w-2/3 p-8 lg:pl-0">
                        <?php 
                        if (isset($_GET['id'])) {
                            $book = $bookController->getBookById($_GET['id']);
                            if (!$book) {
                                exit;
                            }
                            
                            echo '<div class="space-y-6">
                                    <!-- Book Title -->
                                    <div>
                                        <h1 class="text-4xl font-bold text-gray-800 mb-2">' . htmlspecialchars($book['title']) . '</h1>
                                        <div class="flex items-center space-x-4 text-gray-600">
                                            <span class="flex items-center">
                                                <i class="fas fa-user mr-2 text-primary"></i>
                                                ' . htmlspecialchars($book['author']) . '
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-barcode mr-2 text-primary"></i>
                                                ISBN: ' . htmlspecialchars($book['isbn']) . '
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Price Section -->
                                    <div class="bg-gradient-to-r from-primary/10 to-primary-hover/10 rounded-2xl p-6 border border-primary/20">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-gray-600 mb-1">Preço</p>
                                                <p class="text-3xl font-bold text-primary">R$ ' . number_format($book['price'], 2, ',', '.') . '</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="flex items-center text-yellow-500 mb-2">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <span class="text-gray-600 text-sm ml-2">(4.8)</span>
                                                </div>
                                                <p class="text-sm text-gray-600">Avaliação dos leitores</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                            <i class="fas fa-book-open mr-2 text-primary"></i>
                                            Sinopse
                                        </h3>
                                        <p class="text-gray-700 leading-relaxed">' . htmlspecialchars($book['description']) . '</p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-4">';
                                        
                                        if(isset($_SESSION['email']) && isset($_SESSION['password'])){
                                            $user = $userController->getIdByEmail($_SESSION['email']);
                                            $isPurchased = $bookController->isBookUser($_GET['id'], $user);
                                            if($isPurchased) {
                                                echo '<a href="./assets/pdfs/' . $book['file'] . '" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center">
                                                        <i class="fas fa-book-reader mr-2"></i>
                                                        Ler Livro
                                                      </a>';
                                            } else {    
                                                echo '<form action="./purchaseAction.php" method="POST" class="flex-1">
                                                        <input type="hidden" name="id_book" value="' . $book['id'] . '">
                                                        <input type="hidden" name="price" value="' . $book['price'] . '">
                                                        <button type="submit" name="buy" class="w-full bg-gradient-to-r from-primary to-primary-hover text-white py-4 px-6 rounded-xl font-semibold hover:from-primary-hover hover:to-primary transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center">
                                                            <i class="fas fa-shopping-cart mr-2"></i>
                                                            Comprar Agora
                                                        </button>
                                                      </form>';
                                            }
                                        } else {
                                            echo '<a href="./login.php" class="flex-1 bg-gradient-to-r from-primary to-primary-hover text-white py-4 px-6 rounded-xl font-semibold hover:from-primary-hover hover:to-primary transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center">
                                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                                    Fazer Login
                                                  </a>';
                                        }
                                        
                                        echo '<button onclick="window.history.back()" class="px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-primary hover:text-primary transition-all duration-300 flex items-center justify-center">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Voltar
                                              </button>
                                    </div>
                                  </div>';
                        }
                        ?> 
                    </div>
                </div>
            </div>

            <!-- Additional Info Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Delivery Info -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Entrega Rápida</h3>
                    </div>
                    <p class="text-gray-600">Receba em até 2 dias úteis em todo o Brasil</p>
                </div>

                <!-- Security Info -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Compra Segura</h3>
                    </div>
                    <p class="text-gray-600">Seus dados protegidos com criptografia SSL</p>
                </div>

                <!-- Support Info -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-headset text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Suporte 24/7</h3>
                    </div>
                    <p class="text-gray-600">Atendimento especializado sempre disponível</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
