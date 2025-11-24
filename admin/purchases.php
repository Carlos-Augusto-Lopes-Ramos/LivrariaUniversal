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
  <title>Gerenciar Compras - Livraria Universal</title>
  
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
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Compras</h1>
            <p class="text-gray-600">Visualize e gerencie todas as compras realizadas</p>
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
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100">
              <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total de Compras</p>
              <p class="text-2xl font-bold text-gray-900"><?php
                include_once '../DAO/Connection.php';
                include_once '../DAO/PurchaseDTO.php';
                include_once '../Model/PurchaseModel.php';
                include_once '../Controller/PurchaseController.php';
                
                $purchaseDTO = new PurchaseDTO($con);
                $purchaseModel = new PurchaseModel($purchaseDTO);
                $purchaseController = new PurchaseController($purchaseModel);
                $totalPurchases = count($purchaseController->getAllPurchases());
                echo $totalPurchases;
              ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100">
              <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Receita Total</p>
              <p class="text-2xl font-bold text-gray-900">R$ <?php
                $totalRevenue = $purchaseController->getTotalRevenue();
                echo number_format($totalRevenue, 2, ',', '.');
              ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100">
              <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Ticket Médio</p>
              <p class="text-2xl font-bold text-gray-900">R$ <?php
                $averageTicket = $totalPurchases > 0 ? $totalRevenue / $totalPurchases : 0;
                echo number_format($averageTicket, 2, ',', '.');
              ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100">
              <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Clientes Ativos</p>
              <p class="text-2xl font-bold text-gray-900"><?php
                $activeCustomers = $purchaseController->getActiveCustomers();
                echo $activeCustomers;
              ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Purchases Table -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-3 text-primary"></i>
            Histórico de Compras
          </h2>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              $purchases = $purchaseController->getAllPurchases();
              
              foreach ($purchases as $purchase) {
                $statusClass = $purchase['status'] == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                $statusText = $purchase['status'] == 'completed' ? 'Concluída' : 'Pendente';
                
                echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $purchase['id'] . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($purchase['user_name']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($purchase['book_title']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R$ ' . number_format($purchase['price'], 2, ',', '.') . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . date('d/m/Y H:i', strtotime($purchase['purchase_date'])) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full ' . $statusClass . '">' . $statusText . '</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                          <button onclick="viewPurchase(' . $purchase['id'] . ')" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                            <i class="fas fa-eye mr-1"></i>Ver
                          </button>
                          <button onclick="editPurchase(' . $purchase['id'] . ')" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors duration-300">
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <form action="../purchaseAction.php" method="POST" class="inline">
                            <input type="hidden" name="id" value="' . $purchase['id'] . '">
                            <button type="submit" name="delete" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors duration-300" onclick="return confirm(\'Tem certeza que deseja deletar esta compra?\')">
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

      <!-- Add/Edit Purchase Form -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mt-8">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-3 text-primary"></i>
            <span id="formTitle">Adicionar Nova Compra</span>
          </h2>
        </div>
        
        <form action="../purchaseAction.php" method="POST" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User ID -->
            <div>
              <label for="id_user" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-primary"></i>ID do Usuário
              </label>
              <input type="text" id="id_user" name="id_user" placeholder="Digite o ID do usuário" required
                     class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- Book ID -->
            <div>
              <label for="id_book" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-book mr-2 text-primary"></i>ID do Livro
              </label>
              <input type="text" id="id_book" name="id_book" placeholder="Digite o ID do livro" required
                     class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- Price -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-dollar-sign mr-2 text-primary"></i>Preço
              </label>
              <input type="number" id="price" name="price" step="0.01" placeholder="Digite o preço" required
                     class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-check-circle mr-2 text-primary"></i>Status
              </label>
              <select id="status" name="status" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300">
                <option value="pending">Pendente</option>
                <option value="completed">Concluída</option>
              </select>
            </div>
          </div>

          <div class="mt-8 flex space-x-4">
            <input type="hidden" name="id" id="purchaseId">
            <button type="submit" name="create" id="createBtn" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-hover transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
              <i class="fas fa-plus mr-2"></i>Criar Compra
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
  </div>
    </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.fixed.inset-y-0.left-0');
      sidebar.classList.toggle('-translate-x-full');
    }

    function viewPurchase(purchaseId) {
      // Implement view purchase functionality
      alert('Visualizar compra ID: ' + purchaseId);
    }

    function editPurchase(purchaseId) {
      // Find the purchase row
      const rows = document.querySelectorAll('tbody tr');
      let targetRow = null;
      
      rows.forEach(row => {
        const idCell = row.querySelector('td:first-child');
        if (idCell && idCell.textContent.trim() == purchaseId) {
          targetRow = row;
        }
      });

      if (!targetRow) return;

      const cells = targetRow.querySelectorAll('td');
      
      // Fill form with purchase data
      document.getElementById('purchaseId').value = cells[0].textContent.trim();
      document.getElementById('id_user').value = cells[1].textContent.trim();
      document.getElementById('id_book').value = cells[2].textContent.trim();
      document.getElementById('price').value = cells[3].textContent.replace('R$ ', '').replace(',', '.');
      document.getElementById('status').value = cells[5].textContent.trim() === 'Concluída' ? 'completed' : 'pending';

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Editar Compra';
      document.getElementById('createBtn').classList.add('hidden');
      document.getElementById('editBtn').classList.remove('hidden');
      document.getElementById('cancelBtn').classList.remove('hidden');

      // Scroll to form
      document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelEdit() {
      // Reset form
      document.querySelector('form').reset();
      document.getElementById('purchaseId').value = '';

      // Update form title and buttons
      document.getElementById('formTitle').textContent = 'Adicionar Nova Compra';
      document.getElementById('createBtn').classList.remove('hidden');
      document.getElementById('editBtn').classList.add('hidden');
      document.getElementById('cancelBtn').classList.add('hidden');
    }
  </script>
</body>
</html>
