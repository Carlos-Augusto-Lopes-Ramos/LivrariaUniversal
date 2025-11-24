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
  <title>Gerenciar Autores - Livraria Universal</title>

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
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Autores</h1>
            <p class="text-gray-600">Adicione, edite ou remova autores do sistema</p>
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
      <!-- Authors Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-3 text-primary"></i>
            Lista de Autores
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              include_once '../DAO/Connection.php';
              include_once '../DAO/AuthorDTO.php';
              include_once '../Model/AuthorModel.php';
              include_once '../Controller/AuthorController.php';

              $authorDTO = new AuthorDTO($con);
              $authorModel = new AuthorModel($authorDTO);
              $authorController = new AuthorController($authorModel);
              $authors = $authorController->getAllAuthors();

              foreach ($authors as $author) {
                echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $author['id'] . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($author['name']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                          <button onclick="editAuthor(' . $author['id'] . ')" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <form action="../authorAction.php" method="POST" class="inline">
                            <input type="hidden" name="id" value="' . $author['id'] . '">
                            <button type="submit" name="delete" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors duration-300" onclick="return confirm(\'Tem certeza que deseja deletar este autor?\')">
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

      <!-- Add/Edit Author Form -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-3 text-primary"></i>
            <span id="formTitle">Adicionar Novo Autor</span>
          </h2>
        </div>

        <form action="../authorAction.php" method="POST" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-primary"></i>Nome do Autor
              </label>
              <input type="text" id="name" name="name" placeholder="Digite o nome do autor" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>
          </div>

          <div class="mt-8 flex space-x-4">
            <input type="hidden" name="id" id="authorId">
            <button type="submit" name="create" id="createBtn" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-hover transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
              <i class="fas fa-plus mr-2"></i>Criar Autor
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

      <!-- Author-Book Links Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mt-8">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-link mr-3 text-primary"></i>
            Relacionar Autor com Livro
          </h2>
        </div>

        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Autor</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Autor</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Livro</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Livro</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $authorBooks = $authorController->getAllBookByAuthor();
                foreach ($authorBooks as $authorBook) {
                  echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $authorBook['id_author'] . '</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($authorBook['author_name']) . '</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . $authorBook['id_book'] . '</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($authorBook['book_title']) . '</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="editAuthorBook(' . $authorBook['id_author'] . ', ' . $authorBook['id_book'] . ')" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                              <i class="fas fa-edit mr-1"></i>Editar
                            </button>
                            <form action="../authorAction.php" method="POST" class="inline">
                              <input type="hidden" name="id_author" value="' . $authorBook['id_author'] . '">
                              <input type="hidden" name="id_book" value="' . $authorBook['id_book'] . '">
                              <button type="submit" name="deleteLink" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors duration-300" onclick="return confirm(\'Tem certeza que deseja remover esta relação?\')">
                                <i class="fas fa-trash mr-1"></i>Remover
                              </button>
                  </form>
                </td>
                </tr>';
                }
                ?>
              </tbody>
            </table>
          </div>

          <!-- Link Author-Book Form -->
          <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Criar Nova Relação</h3>
            <form action="../authorAction.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="id_author" class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-user mr-2 text-primary"></i>ID do Autor
                </label>
                <input type="text" id="id_author" name="id_author" placeholder="Digite o ID do autor" required
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
                <button type="submit" name="createLink" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-hover transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
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

    function editAuthor(authorId) {
      // Find the author row
      const rows = document.querySelectorAll('tbody tr');
      let targetRow = null;

      rows.forEach(row => {
        const idCell = row.querySelector('td:first-child');
        if (idCell && idCell.textContent.trim() == authorId) {
          targetRow = row;
        }
      });

      if (!targetRow) return;

      const cells = targetRow.querySelectorAll('td');

      // Fill form with author data
      document.getElementById('authorId').value = cells[0].textContent.trim();
      document.getElementById('name').value = cells[1].textContent.trim();

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Editar Autor';
      document.getElementById('createBtn').classList.add('hidden');
      document.getElementById('editBtn').classList.remove('hidden');
      document.getElementById('cancelBtn').classList.remove('hidden');

      // Scroll to form
      document.querySelector('form').scrollIntoView({
        behavior: 'smooth'
      });
    }

    function editAuthorBook(authorId, bookId) {
      // Fill the link form with existing data
      document.getElementById('id_author').value = authorId;
      document.getElementById('id_book').value = bookId;
    }

    function cancelEdit() {
      // Reset form
      document.querySelector('form').reset();
      document.getElementById('authorId').value = '';

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Adicionar Novo Autor';
      document.getElementById('createBtn').classList.remove('hidden');
      document.getElementById('editBtn').classList.add('hidden');
      document.getElementById('cancelBtn').classList.add('hidden');
    }
  </script>
</body>

</html>