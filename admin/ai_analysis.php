<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Incluir conexão com banco
include_once('../DAO/Connection.php');
include_once('../DAO/BookDTO.php');
include_once('../DAO/UserDTO.php');
include_once('../Model/BookModel.php');
include_once('../Model/UserModel.php');
include_once('../Controller/BookController.php');
include_once('../Controller/UserController.php');

try {
    $bookDTO = new BookDTO($con);
    $bookModel = new BookModel($bookDTO);
    $bookController = new BookController($bookModel);

    $userDTO = new UserDTO($con);
    $userModel = new UserModel($userDTO);
    $userController = new UserController($userModel);

    // Coletar dados do dashboard
    $totalBooks = count($bookController->getAllActiveBooks());
    $totalUsers = count($userController->getAllUsers());

    // Calcular receita total (simulado)
    $books = $bookController->getAllActiveBooks();
    $totalRevenue = 0;
    $totalPrice = 0;
    $purchaseDTO = new PurchaseDTO($con);

    foreach ($books as $book) {
          $totalPrice += $book['price'];
          // Simular vendas baseadas no ID do livro
          $sales = ($book['id'] % 10) + 1;
          $totalRevenue += $book['price'] * $sales;
        }

        $avgBookPrice = $totalBooks > 0 ? $totalPrice / $totalBooks : 0;

    $salesData = $purchaseDTO->getSalesByMonth();
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

    // Chamada para API Gemini
    $apiKey = 'AIzaSyBVM3QTN4jQQZiR2BRF55jHteajiR5iOYA';
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $prompt = "Analise os seguintes dados de uma livraria online e forneça insights inteligentes em português brasileiro:

DADOS DA LIVRARIA:
- Total de livros: {$totalBooks}
- Total de usuários: {$totalUsers}
- Receita total: R$ " . $salesData . "
- Preço médio dos livros: R$ " . $avgBookPrice . "
- Distribuição por categoria: " . json_encode($dashboardData['booksByCategory']['labels']) . json_encode($dashboardData['booksByCategory']['values']) . "

Por favor, forneça uma análise estruturada com:
1. INSIGHTS DE MERCADO: Análise do desempenho atual (2-3 frases)
2. RECOMENDAÇÕES: Sugestões estratégicas para crescimento (2-3 frases)
3. ALERTAS: Pontos de atenção importantes (1-2 frases)
4. TENDÊNCIAS: Identificação de padrões e oportunidades (2-3 frases)

Responda de forma clara, objetiva e profissional.";

    $data = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $prompt
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 1024
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $result = json_decode($response, true);

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $aiAnalysis = $result['candidates'][0]['content']['parts'][0]['text'];

            // Processar resposta da IA
            $sections = explode("\n", $aiAnalysis);
            $processedAnalysis = [
                'marketInsights' => '',
                'recommendations' => '',
                'alerts' => '',
                'trends' => ''
            ];

            $currentSection = '';
            foreach ($sections as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                if (stripos($line, 'INSIGHTS') !== false || stripos($line, 'MERCADO') !== false) {
                    $currentSection = 'marketInsights';
                } elseif (stripos($line, 'RECOMENDAÇÕES') !== false || stripos($line, 'RECOMENDA') !== false) {
                    $currentSection = 'recommendations';
                } elseif (stripos($line, 'ALERTAS') !== false || stripos($line, 'ALERTA') !== false) {
                    $currentSection = 'alerts';
                } elseif (stripos($line, 'TENDÊNCIAS') !== false || stripos($line, 'TENDENCIA') !== false) {
                    $currentSection = 'trends';
                } elseif ($currentSection && !preg_match('/^\d+\./', $line)) {
                    $processedAnalysis[$currentSection] .= $line . ' ';
                }
            }

            // Limpar e formatar
            foreach ($processedAnalysis as $key => $value) {
                $processedAnalysis[$key] = trim($value);
                if (empty($processedAnalysis[$key])) {
                    $processedAnalysis[$key] = 'Análise em andamento...';
                }
            }

            echo json_encode([
                'success' => true,
                'analysis' => $processedAnalysis,
                'rawData' => $dashboardData
            ]);
        } else {
            throw new Exception('Resposta inválida da API Gemini');
        }
    } else {
        throw new Exception('Erro na comunicação com a API Gemini');
    }
} catch (Exception $e) {
    // Fallback com análise simulada
    $fallbackAnalysis = [
        'marketInsights' => 'A livraria apresenta um catálogo sólido com ' . $totalBooks . ' livros e ' . $totalUsers . ' usuários cadastrados. O crescimento está dentro das expectativas do mercado.',
        'recommendations' => 'Recomendamos expandir o catálogo de livros técnicos e educacionais, além de implementar um sistema de recomendações personalizadas para aumentar as vendas.',
        'alerts' => 'Monitorar o preço médio dos livros para manter competitividade no mercado e acompanhar a satisfação dos usuários.',
        'trends' => 'Tendência crescente de digitalização e preferência por livros técnicos e educacionais. O mercado está se movendo para formatos digitais.'
    ];

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'analysis' => $fallbackAnalysis,
        'rawData' => $dashboardData ?? []
    ]);
}
