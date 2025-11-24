<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] != 1) {
  header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gerenciar Livros - Livraria Universal</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../assets/css/custom.css" rel="stylesheet">

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
  <!-- Sidebar -->
 <?php
  
    include_once("../component/navBar.php");

  ?>

  <!-- Main Content -->
  <div class="lg:ml-64">
    <!-- Top Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Livros</h1>
            <p class="text-gray-600">Adicione, edite ou remova livros do catálogo</p>
          </div>
          <div class="flex items-center space-x-4">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
              <i class="fas fa-bars text-gray-600"></i>
            </button>
            <a href="../index.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors duration-300">
              <i class="fas fa-external-link-alt mr-2"></i>
              Ver Site
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div class="p-6">
      <!-- Books Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 h-96 overflow-auto">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-3 text-primary"></i>
            Lista de Livros
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagem</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arquivo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 h-full">
              <?php
              include_once '../DAO/Connection.php';
              include_once '../DAO/BookDTO.php';
              include_once '../DAO/CategoriesDTO.php';
              include_once '../Model/BookModel.php';
              include_once '../Model/CategoryModel.php';
              include_once '../Controller/BookController.php';
              include_once '../Controller/CategoryController.php';

              $bookDTO = new BookDTO($con);
              $bookModel = new BookModel($bookDTO);
              $bookController = new BookController($bookModel);
              $books = $bookController->getAllActiveBooks();

              foreach ($books as $book) {
                echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $book['id'] . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($book['title']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($book['isbn']) . '</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">' . htmlspecialchars($book['description']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($book['image']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($book['file']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">R$ ' . number_format($book['price'], 2, ',', '.') . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                          <button onclick="editBook(' . $book['id'] . ')" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <form action="../bookAction.php" method="POST" class="inline">
                            <input type="hidden" name="id" value="' . $book['id'] . '">
                            <button type="submit" name="delete" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors duration-300" onclick="return confirm(\'Tem certeza que deseja deletar este livro?\')">
                              <i class="fas fa-trash mr-1"></i>Deletar
                            </button>
                  </form>
                </td>
                </tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Add/Edit Book Form -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-3 text-primary"></i>
            <span id="formTitle">Adicionar Novo Livro</span>
          </h2>
        </div>

        <form action="../bookAction.php" method="POST" enctype="multipart/form-data" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
              <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-book mr-2 text-primary"></i>Título do Livro
              </label>
              <input type="text" id="title" name="title" placeholder="Digite o título do livro" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-align-left mr-2 text-primary"></i>Descrição
              </label>
              <textarea id="description" name="description" rows="4" placeholder="Digite a descrição do livro" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 resize-none"></textarea>
            </div>

            <!-- Price -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-dollar-sign mr-2 text-primary"></i>Preço
              </label>
              <input type="number" id="price" name="price" step="0.01" placeholder="49.99" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- ISBN -->
            <div>
              <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-barcode mr-2 text-primary"></i>ISBN
              </label>
              <input type="text" id="isbn" name="isbn" placeholder="1234567890123" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- Image File -->
            <div>
              <label for="imageFile" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-image mr-2 text-primary"></i>Capa do Livro
              </label>
              <input type="file" id="imageFile" name="imageFile" accept="image/*"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- PDF File -->
            <div>
              <label for="pdf" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-file-pdf mr-2 text-primary"></i>Arquivo PDF
              </label>
              <input type="file" id="pdf" name="pdf" accept="application/pdf"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>
          </div>

          <div class="mt-8 flex space-x-4">
            <input type="hidden" name="id" id="bookId">
            <button type="submit" name="create" id="createBtn" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-hover transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
              <i class="fas fa-plus mr-2"></i>Criar Livro
            </button>
            <button type="submit" name="edit" id="editBtn" class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hidden">
              <i class="fas fa-save mr-2"></i>Salvar Alterações
            </button>
            <button type="button" onclick="cancelEdit()" id="cancelBtn" class="bg-gray-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-600 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hidden">
              <i class="fas fa-times mr-2"></i>Cancelar
            </button>
          </div>

        </form>

      </div>

      <!-- Seção de Criação de Categorias -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Gerenciar Categorias</h3>
        <p class="text-gray-600 mb-4">Adicione novas categorias de livros para organizar melhor o catálogo.</p>

        <!-- Formulário para criar categoria -->
        <form action="./CategoryService.php" method="POST" class="space-y-4">
          <div>
            <label for="category_name" class="block text-sm font-medium text-gray-700">Nome da Categoria</label>
            <input type="text" id="nome" name="nome" required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
              placeholder="Ex: Fantasia, Tecnologia, História...">
          </div>
          <button type="submit" name="create" id="create"
            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover transition-colors duration-300">
            <i class="fas fa-plus mr-2"></i>Criar Categoria
          </button>
        </form>
        <!-- Lista de Categorias Existentes -->
        <div class="mt-8 overflow-auto h-96">
          <h4 class="text-lg font-semibold text-gray-800 mb-2">Categorias Existentes</h4>
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200"></tbody>

              <?php

              $categoryDTO = new CategoriesDTO($con);
              $categoryModel = new CategoryModel($categoryDTO);
              $categoryController = new CategoryController($categoryModel);
              $categories = $categoryController->getAllCategories();

              foreach ($categories as $category) {
                echo '<tr class="hover:bg-gray-50 transition-colors duration-200 w-full">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $category['id'] . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($category['nome']) . '</td>
                </tr>';
              }
              if ($categories == null) {
                echo '<p class="text-gray-500">Nenhuma categoria cadastrada ainda.</p>';
              }


              ?>
              </tbody>
            </table>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Categoria</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Livro</th>
                  
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $categories = $categoryController->getAllCategoriesLink();
                foreach ($categories as $category) {
                  echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $category['categoria_id'] . '</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . $category['livro_id'] . '</td>
                          
                  </form>
                </td>
                </tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
          <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Criar Nova Relação</h3>
            <form action="./CategoryService.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="id_author" class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-user mr-2 text-primary"></i>ID da Categoria
                </label>
                <input type="text" id="id_categoria" name="id_categoria" placeholder="Digite o ID da categoria" required
                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
              </div>
              <div>
                <label for="id_book" class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-book mr-2 text-primary"></i>ID do Livro
                </label>
                <input type="text" id="id_book" name="id_book" placeholder="Digite o ID do livro" required
                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
              </div>
              <div class="md:col-span-2">
                <button type="submit" name="link" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-hover transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                  <i class="fas fa-link mr-2"></i>Criar Relação
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>

  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.fixed.inset-y-0.left-0');
      sidebar.classList.toggle('-translate-x-full');
    }

    function editBook(bookId) {
      // Find the book row
      const rows = document.querySelectorAll('tbody tr');
      let targetRow = null;

      rows.forEach(row => {
        const idCell = row.querySelector('td:first-child');
        if (idCell && idCell.textContent.trim() == bookId) {
          targetRow = row;
        }
      });

      if (!targetRow) return;

      const cells = targetRow.querySelectorAll('td');

      // Fill form with book data
      document.getElementById('bookId').value = cells[0].textContent.trim();
      document.getElementById('title').value = cells[1].textContent.trim();
      document.getElementById('isbn').value = cells[2].textContent.trim();
      document.getElementById('description').value = cells[3].textContent.trim();
      document.getElementById('price').value = cells[6].textContent.replace('R$ ', '').replace(',', '.');

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Editar Livro';
      document.getElementById('createBtn').classList.add('hidden');
      document.getElementById('editBtn').classList.remove('hidden');
      document.getElementById('cancelBtn').classList.remove('hidden');

      // Scroll to form
      document.querySelector('form').scrollIntoView({
        behavior: 'smooth'
      });
    }

    function cancelEdit() {
      // Reset form
      document.querySelector('form').reset();
      document.getElementById('bookId').value = '';

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Adicionar Novo Livro';
      document.getElementById('createBtn').classList.remove('hidden');
      document.getElementById('editBtn').classList.add('hidden');
      document.getElementById('cancelBtn').classList.add('hidden');
    }
  </script>
</body>

</html>