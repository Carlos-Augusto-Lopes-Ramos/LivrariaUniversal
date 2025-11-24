<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] != 1) {
  header("Location: ../index.php");
  exit;
}

// Incluir dependências e calcular dados do dashboard
include_once("../DAO/Connection.php");
include_once("../DAO/BookDTO.php");
include_once("../DAO/UserDTO.php");
include_once("../DAO/PurchaseDTO.php");
include_once("../Model/BookModel.php");
include_once("../Model/UserModel.php");
include_once("../Model/PurchaseModel.php");
include_once("../Controller/BookController.php");
include_once("../Controller/UserController.php");
include_once("../Controller/PurchaseController.php");

$bookDTO = new BookDTO($con);
$bookController = new BookController(new BookModel($bookDTO));

$userDTO = new UserDTO($con);
$userController = new UserController(new UserModel($userDTO));

$purchaseDTO = new PurchaseDTO($con);
$purchaseController = new PurchaseController(new PurchaseModel($purchaseDTO));

// Dados gerais
$totalBooks = count($bookController->getAllActiveBooks());
$totalUsers = count($userController->getAllUsers());

$books = $bookController->getAllActiveBooks();
$totalRevenue = 0;
$totalPrice = 0;
foreach ($books as $book) {
  $totalPrice += $book['price'];
  $sales = ($book['id'] % 10) + 1;
  $totalRevenue += $book['price'] * $sales;
}
$avgBookPrice = $totalBooks > 0 ? $totalPrice / $totalBooks : 0;

// Categorias
$sql = "
SELECT c.nome AS categoria, COUNT(*) AS total
FROM livro_categoria lc
INNER JOIN categorias c ON c.id = lc.categoria_id
GROUP BY lc.categoria_id
ORDER BY total DESC
";
$stmt = $con->query($sql);
$categoriasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dashboardData = [
  'booksByCategory' => [
    'labels' => array_column($categoriasData, 'categoria'),
    'values' => array_map('intval', array_column($categoriasData, 'total'))
  ],
  'totalBooks' => $totalBooks,
  'totalUsers' => $totalUsers,
  'totalRevenue' => $totalRevenue,
  'avgBookPrice' => $avgBookPrice
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Livraria Universal</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../assets/css/custom.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
  <script>
    // Fallback para Chart.js
    if (typeof Chart === 'undefined') {
      console.error('Chart.js não carregou, tentando CDN alternativo...');
      const script = document.createElement('script');
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js';
      script.onload = function() {
        console.log('Chart.js carregado com sucesso via CDN alternativo');
      };
      script.onerror = function() {
        console.error('Falha ao carregar Chart.js');
      };
      document.head.appendChild(script);
    }
  </script>

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
  include_once('../DAO/Connection.php');
  include_once('../DAO/BookDTO.php');
  include_once('../DAO/UserDTO.php');
  include_once('../Model/BookModel.php');
  include_once('../Model/UserModel.php');
  include_once('../Controller/BookController.php');
  include_once('../Controller/UserController.php');


  ?>
  <!-- Main Content -->
  <div class="lg:ml-64">
    <!-- Top Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrativo</h1>
            <p class="text-gray-600">Visão geral do sistema e estatísticas</p>
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
      <!-- AI Analysis Section -->
      <div class="mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-xl font-semibold text-gray-800">Análise de Inteligência Artificial</h2>
            <p class="text-gray-600">Insights automáticos baseados nos dados do sistema</p>
          </div>
          <div class="flex items-center space-x-4">
            <button id="aiAnalysisBtn" class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-6 py-2 rounded-lg hover:from-purple-600 hover:to-blue-600 transition-all duration-300 flex items-center">
              <i class="fas fa-robot mr-2"></i>
              Análise IA
            </button>
            <span class="text-sm text-gray-500">Última atualização: <?php echo date('d/m/Y H:i'); ?></span>
          </div>
        </div>
      </div>

      <!-- Cards de Estatísticas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php
        include_once("../DAO/Connection.php");
        include_once("../DAO/BookDTO.php");
        include_once("../DAO/UserDTO.php");
        include_once("../Model/BookModel.php");
        include_once("../Model/UserModel.php");
        include_once("../Controller/BookController.php");
        include_once("../Controller/UserController.php");

        $bookDTO = new BookDTO($con);
        $bookModel = new BookModel($bookDTO);
        $bookController = new BookController($bookModel);

        $userDTO = new UserDTO($con);
        $userModel = new UserModel($userDTO);
        $userController = new UserController($userModel);

        // Estatísticas
        $totalBooks = count($bookController->getAllActiveBooks());
        $totalUsers = count($userController->getAllUsers());

        // Calcular receita total e preço médio dos livros
        $books = $bookController->getAllActiveBooks();
        $totalRevenue = 0;
        $totalPrice = 0;

        foreach ($books as $book) {
          $totalPrice += $book['price'];
          // Simular vendas baseadas no ID do livro
          $sales = ($book['id'] % 10) + 1;
          $totalRevenue += $book['price'] * $sales;
        }

        $avgBookPrice = $totalBooks > 0 ? $totalPrice / $totalBooks : 0;
        ?>

        <!-- Total de Livros -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total de Livros</p>
              <p class="text-3xl font-bold text-gray-900"><?php echo $totalBooks; ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
              <i class="fas fa-book text-blue-600 text-xl"></i>
            </div>
          </div>
        </div>

        <!-- Total de Usuários -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total de Usuários</p>
              <p class="text-3xl font-bold text-gray-900"><?php echo $totalUsers; ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
              <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
          </div>
        </div>

        <!-- Receita Total -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Receita Total</p>
              <p class="text-3xl font-bold text-gray-900"><?php
                                                          include_once '../DAO/PurchaseDTO.php';
                                                          include_once '../Model/PurchaseModel.php';
                                                          include_once '../Controller/PurchaseController.php';
                                                          $purchaseDTO = new PurchaseDTO($con);
                                                          $purchaseModel = new PurchaseModel($purchaseDTO);
                                                          $purchaseController = new PurchaseController($purchaseModel);
                                                          $purchaseDTO = new PurchaseDTO($con);
                                                          $purchaseModel = new PurchaseModel($purchaseDTO);
                                                          $purchaseController = new PurchaseController($purchaseModel);
                                                          $totalRevenue = $purchaseController->getTotalRevenue();
                                                          echo number_format($totalRevenue, 2, ',', '.');
                                                          ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
              <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
            </div>
          </div>
        </div>

        <!-- Preço Médio do Livro -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Preço Médio</p>
              <p class="text-3xl font-bold text-gray-900">R$ <?php echo number_format($avgBookPrice, 2, ',', '.'); ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
              <i class="fas fa-tag text-red-600 text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- AI Analysis Section -->
      <div id="aiAnalysisSection" class="bg-white rounded-xl shadow-sm p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold text-gray-800">Resultados da Análise de IA</h3>
          <button id="closeAiAnalysis" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times">

            </i>

          </button>
        </div>
        <div id="aiLoading" class="text-center py-8">
          <i class="fas fa-spinner fa-spin text-primary text-4xl mb-4"></i>
          <p class="text-gray-600">Gerando insights de IA, por favor aguarde...</p>
        </div>
        <div id="aiResults" class="hidden">

          <div class="mb-4">
            <h4 class="font-semibold text-gray-800">Recomendações:</h4>
            
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Vendas por Mês</h3>
          <div class="relative h-64">
            <canvas id="salesChart"></canvas>
          </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Livros por Categoria</h3>
          <div class="relative h-64">
            <canvas id="categoryChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Top Books Table -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Livros Mais Vendidos</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Título
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Autor
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Vendas
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Preço
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              $topBooks = $bookController->getAllActiveBooks();
              $topBooks = array_slice($topBooks, 0, 5); // Pegar os 5 primeiros
              foreach ($topBooks as $book) {
                echo '<tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . htmlspecialchars($book['title']) . '</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($book['author']) . '</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . $book['total_vendas'] . '</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ ' . number_format($book['price'], 2, ',', '.') . '</td>
                          </tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
  <div class="w-full h-11 flex items-center justify-center">
    <button 
        onclick="baixar()" 
        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition-all">
        Baixar PDF
    </button>
</div>


  <script>
    // Função para alternar a visibilidade da sidebar em telas pequenas
    function toggleSidebar() {
      const sidebar = document.querySelector('.fixed.inset-y-0.left-0');
      sidebar.classList.toggle('-translate-x-full');
    }

    // Inicializar gráficos quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
      // Aguardar um pouco para garantir que o Chart.js esteja carregado
      initializeCharts();
    });

    function initializeCharts() {
      <?php
      $purchaseDTO = new PurchaseDTO($con);
      $salesData = $purchaseDTO->getSalesByMonth();

      // Ordem correta dos meses abreviados em inglês
      $monthOrder = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

      // Transformar os dados em um array associativo
      $salesAssoc = [];
      foreach ($salesData as $item) {
        $salesAssoc[$item['month']] = (float)$item['total'];
      }

      // Preencher os arrays na ordem correta
      $labels = [];
      $values = [];
      foreach ($monthOrder as $month) {
        $labels[] = $month;
        $values[] = $salesAssoc[$month] ?? 0; // Se não houver vendas no mês, coloca 0
      }
      ?>



      const salesLabelss = <?= json_encode($labels) ?>;
      const salesValuess = <?= json_encode($values) ?>;
      // Gráfico de Vendas

      const salesCtx = document.getElementById('salesChart');

      if (salesCtx && typeof Chart !== 'undefined') {
        new Chart(salesCtx, {
          type: 'line',
          data: {
            labels: salesLabelss, // vindo do PHP
            datasets: [{
              label: 'Vendas',
              data: salesValuess, // vindo do PHP
              borderColor: '#00A9A5',
              backgroundColor: 'rgba(0, 169, 165, 0.1)',
              tension: 0.4,
              fill: true,
              pointBackgroundColor: '#00A9A5',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true
              },
              x: {
                grid: {
                  display: false
                }
              }
            }
          }
        });
      }


      <?php

      include_once '../DAO/Connection.php';
      $pdo = $con;
      // Busca todas as categorias com total de livros
      $sql = "
    SELECT c.nome AS categoria, COUNT(*) AS total
    FROM livro_categoria lc
    INNER JOIN categorias c ON c.id = lc.categoria_id
    GROUP BY lc.categoria_id
    ORDER BY total DESC
";

      $stmt = $pdo->query($sql);
      $categoriasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Separar labels e valores
      $categoryLabels = [];
      $categoryValues = [];

      foreach ($categoriasData as $row) {
        $categoryLabels[] = $row['categoria'];
        $categoryValues[] = (int)$row['total'];
      }

      // Inserir no dashboardData
      $dashboardData['booksByCategory'] = [
        'labels' => $categoryLabels,
        'values' => $categoryValues
      ];


      ?>
      const categoryCtx = document.getElementById('categoryChart');

      const categoryLabels = <?php echo json_encode($dashboardData['booksByCategory']['labels']); ?>;
      const categoryValues = <?php echo json_encode($dashboardData['booksByCategory']['values']); ?>;

      if (categoryCtx && typeof Chart !== 'undefined') {
        new Chart(categoryCtx, {
          type: 'doughnut',
          data: {
            labels: categoryLabels,
            datasets: [{
              data: categoryValues,
              backgroundColor: [
                '#00A9A5',
                '#007E79',
                '#FF6B6B',
                '#4ECDC4',
                '#45B7D1',
                '#9b59b6',
                '#e67e22'
              ],
              borderWidth: 0,
              hoverOffset: 10
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 20,
                  usePointStyle: true,
                  font: {
                    size: 12
                  }
                }
              }
            }
          }
        });
      }
    }
  </script>

  <?php
  $bookDTO = new BookDTO($con);
  $bookModel = new BookModel($bookDTO);
  $bookController = new BookController($bookModel);

  $userDTO = new UserDTO($con);
  $userModel = new UserModel($userDTO);
  $userController = new UserController($userModel);

  // Coletar dados do dashboard
  $totalBooks = count($bookController->getAllActiveBooks());
  $totalUsers = count($userController->getAllUsers());

  // Receita total simulada
  $books = $bookController->getAllActiveBooks();
  $totalRevenue = 0;
  $totalPrice = 0;
  $purchaseDTO = new PurchaseDTO($con);

  foreach ($books as $book) {
    $totalPrice += $book['price'];

    // Simular vendas baseadas no ID
    $sales = ($book['id'] % 10) + 1;
    $totalRevenue += $book['price'] * $sales;
  }

  $avgBookPrice = $totalBooks > 0 ? $totalPrice / $totalBooks : 0;

  // Vendas por mês
  $salesData = $purchaseDTO->getSalesByMonth();

  // Categorias
  $categoriasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $categoryLabels = [];
  $categoryValues = [];

  foreach ($categoriasData as $row) {
    $categoryLabels[] = $row['categoria'];
    $categoryValues[] = (int)$row['total'];
  }

  // AGORA montar o dashboardData
  $dashboardData = [
    'totalBooks' => $totalBooks,
    'totalUsers' => $totalUsers,
    'totalRevenue' => $totalRevenue,
    'avgBookPrice' => $avgBookPrice,
    'salesByMonth' => $salesData,
    'booksByCategory' => [
      'labels' => $categoryLabels,
      'values' => $categoryValues
    ]
  ];

  ?>
  <script>
    // Dados do PHP para JS
    const dashboardData = <?php echo json_encode($dashboardData, JSON_UNESCAPED_UNICODE); ?>;
    console.log(dashboardData.toString());

    async function enviarParaGemini(promptero) {
      const apiKey = "AIzaSyAbfHsWAxCeeR0d0Cw-kW3GUlS5H8m11g8"; // ⚠️ Não exponha em produção
      const url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" + apiKey ;

      const payload = {
        contents: {
          
            parts: {
              text: promptero
            }
          
        }
      };

      try {
        const res = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        console.log(data);
        return data.candidates?.[0]?.content?.parts?.[0]?.text || 'Sem resposta';
      } catch (err) {
        console.error(err);
        return 'Erro ao chamar Gemini';
      }
    }

    document.getElementById('aiAnalysisBtn').addEventListener('click', async () => {
      const promptFinal = `
Analise os seguintes dados de uma livraria online:
- Total de livros: ${dashboardData.totalBooks}
- Total de usuários: ${dashboardData.totalUsers}
- Receita total: R$4.135,00
- Preço médio dos livros: ${dashboardData.avgBookPrice}

Analise os dados abaixo e gere o resultado exatamente no formato indicado.
Nao use JSON, nao use blocos de codigo, nao use crases, nao use aspas duplas.
Use exatamente o modelo abaixo:

{
      "content": [
      {
        "nome": "INSIGHTS DE MERCADO",
        "dica": ""
      },
      {
        "nome": "RECOMENDAÇÕES",
        "dica": ""
      },
      {
        "nome": "ALERTAS",
        "dica": ""
      },
      {
        "nome": "TENDÊNCIAS",
        "dica": ""
      },

      ]
}

`;
console.log(promptFinal)
      const resultado = await enviarParaGemini(promptFinal);
      var nobaru = JSON.parse(resultado);
      var nibiru = `<div class="p-4 mb-4 bg-blue-100 text-blue-800 rounded">${nobaru.content[0].nome}: ${nobaru.content[0].dica}</div>
<div class="p-4 mb-4 bg-green-100 text-green-800 rounded">${nobaru.content[1].nome}: ${nobaru.content[1].dica}</div>
<div class="p-4 mb-4 bg-red-100 text-red-800 rounded">${nobaru.content[2].nome}: ${nobaru.content[2].dica}</div>
<div class="p-4 mb-4 bg-yellow-100 text-yellow-800 rounded">${nobaru.content[3].nome}: ${nobaru.content[3].dica}</div>`;
nibiru = nibiru.replace("\\n", "");
      document.getElementById('aiResults').innerHTML= nibiru;
    });
    function baixar() {
  const elemento = document.getElementById("mainContent");

  const opt = {
    margin:       0,
    filename:     "documento.jpeg",
    image:        { type: "jpeg", quality: 0.98 },
    html2canvas:  { scale: 3, useCORS: true },
    jsPDF:        { unit: "mm", format: "a3", orientation: "portrait" },
    pagebreak:    { mode: ["css", "legacy"] } // evita cortar conteúdo
  };

 // html2pdf().set(opt).from(elemento).save();
 var navBar = document.getElementById("navBar");
 navBar.classList.remove("ml-64"); 
 navBar.style.display = "none";
 window.print();
 navBar.style.display = "block";
}
  </script>
  <!-- Charts JavaScript -->
  <script src="./charts.js"></script>

  <!-- Dashboard JavaScript -->
  <script src="./dashboard.js"></script>


</body>

</html>