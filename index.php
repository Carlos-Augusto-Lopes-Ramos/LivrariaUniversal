<?php 
  session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livraria Universal - Sua livraria online</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
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
            'cart-red': '#FF3D57',
            'mercadolivre': '#FFE600',
            'shopee': '#EE4D2D'
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-50">

  <?php
  include("./component/header.php");
  ?>

  <main class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-primary via-primary-hover to-primary overflow-hidden">
      <div class="absolute inset-0 bg-black/10"></div>
      <div class="relative container mx-auto px-4 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
              Descubra o mundo da
              <span class="text-yellow-300">leitura</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
              Milhares de livros esperando por você. Encontre sua próxima aventura literária.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
              <a href="./catalogo.php" class="bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg text-center">
                <i class="fas fa-book mr-2"></i>
                Explorar Livros
              </a>
              <a href="./catalogo.php#filtros" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-primary transition-all duration-300 text-center">
                <i class="fas fa-list mr-2"></i>
                Ver Categorias
              </a>
            </div>
          </div>
          <div class="relative">
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/20 rounded-xl p-4 text-center">
                  <i class="fas fa-book text-3xl mb-2"></i>
                  <div class="text-2xl font-bold">10,000+</div>
                  <div class="text-sm opacity-80">Livros</div>
                </div>
                <div class="bg-white/20 rounded-xl p-4 text-center">
                  <i class="fas fa-users text-3xl mb-2"></i>
                  <div class="text-2xl font-bold">50,000+</div>
                  <div class="text-sm opacity-80">Leitores</div>
                </div>
                <div class="bg-white/20 rounded-xl p-4 text-center">
                  <i class="fas fa-star text-3xl mb-2"></i>
                  <div class="text-2xl font-bold">4.9</div>
                  <div class="text-sm opacity-80">Avaliação</div>
                </div>
                <div class="bg-white/20 rounded-xl p-4 text-center">
                  <i class="fas fa-truck text-3xl mb-2"></i>
                  <div class="text-2xl font-bold">24h</div>
                  <div class="text-sm opacity-80">Entrega</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-16">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Por que escolher a Livraria Universal?</h2>
          <p class="text-xl text-gray-600">Oferecemos a melhor experiência de compra de livros online</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center group">
            <div class="bg-primary/10 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors duration-300">
              <i class="fas fa-shipping-fast text-primary text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Entrega Rápida</h3>
            <p class="text-gray-600">Receba seus livros em até 24 horas com frete grátis para todo o Brasil</p>
          </div>
          <div class="text-center group">
            <div class="bg-primary/10 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors duration-300">
              <i class="fas fa-shield-alt text-primary text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Compra Segura</h3>
            <p class="text-gray-600">Seus dados protegidos com criptografia SSL e garantia de satisfação</p>
          </div>
          <div class="text-center group">
            <div class="bg-primary/10 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors duration-300">
              <i class="fas fa-headset text-primary text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Suporte 24/7</h3>
            <p class="text-gray-600">Atendimento especializado sempre disponível para ajudar você</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-gray-50 py-16" id="categorias">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Explore por Categorias</h2>
          <p class="text-xl text-gray-600">Encontre exatamente o que você está procurando</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
              <i class="fas fa-book text-blue-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Ficção</h3>
            <p class="text-sm text-gray-500 mt-1">1,200+ livros</p>
          </div>
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
              <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Educação</h3>
            <p class="text-sm text-gray-500 mt-1">800+ livros</p>
          </div>
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
              <i class="fas fa-heart text-purple-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Romance</h3>
            <p class="text-sm text-gray-500 mt-1">950+ livros</p>
          </div>
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors">
              <i class="fas fa-rocket text-red-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Tecnologia</h3>
            <p class="text-sm text-gray-500 mt-1">600+ livros</p>
          </div>
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-200 transition-colors">
              <i class="fas fa-child text-yellow-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Infantil</h3>
            <p class="text-sm text-gray-500 mt-1">400+ livros</p>
          </div>
          <div class="bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-2 cursor-pointer group">
            <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
              <i class="fas fa-chart-line text-indigo-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800">Negócios</h3>
            <p class="text-sm text-gray-500 mt-1">350+ livros</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Call to Action Section -->
    <div class="bg-white py-16" id="livros">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pronto para começar sua jornada literária?</h2>
          <p class="text-xl text-gray-600">Explore nossa vasta coleção de livros e encontre sua próxima leitura favorita</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
          <div class="bg-gradient-to-br from-primary to-primary-hover rounded-2xl p-8 text-white text-center">
            <div class="bg-white/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-book text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Explorar Catálogo</h3>
            <p class="text-white/90 mb-6">Descubra milhares de livros organizados por categoria, autor e preço</p>
            <a href="./catalogo.php" class="bg-white text-primary px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300 inline-block">
              <i class="fas fa-search mr-2"></i>
              Ver Catálogo Completo
            </a>
          </div>
          
          <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 text-white text-center">
            <div class="bg-white/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-list text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Navegar por Categorias</h3>
            <p class="text-white/90 mb-6">Encontre exatamente o que procura com nossos filtros inteligentes</p>
            <a href="./catalogo.php#filtros" class="bg-white text-gray-800 px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300 inline-block">
              <i class="fas fa-filter mr-2"></i>
              Filtrar Livros
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Newsletter Section -->
    <div class="bg-gradient-to-r from-primary to-primary-hover py-16">
      <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto">
          <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Receba nossas novidades</h2>
          <p class="text-xl text-white opacity-90 mb-8">Fique por dentro dos lançamentos e promoções exclusivas</p>
          <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" placeholder="Seu melhor e-mail" class="flex-1 px-6 py-4 rounded-xl border-none outline-none text-gray-800">
            <button class="bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300">
              <i class="fas fa-paper-plane mr-2"></i>
              Inscrever
            </button>
          </div>
        </div>
      </div>
    </div>

    <?php
    include("./component/footer.php");
    ?>
  </main>

  <script src="./assets/js/script.js"></script>
</body>
</html>