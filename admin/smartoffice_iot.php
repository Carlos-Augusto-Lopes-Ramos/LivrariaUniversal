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
  <title>Monitoramento IoT - Smart Office 4.0</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../assets/css/custom.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

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
  <div class="ml-64 max-w-full" id="mainContent">
    <!-- Top Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <button class="lg:hidden text-gray-600 focus:outline-none" id="sidebarToggle">
              <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800 ml-2 lg:ml-0">Monitoramento IoT</h1>
          </div>
          <div class="flex items-center">
            <div class="relative">
              <span class="text-gray-600"><?php echo $_SESSION['email']; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div class="p-6">
      <!-- AI Analysis Section -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-robot mr-3 text-primary"></i>
            Análise de IA - Monitoramento IoT
          </h2>
          <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Atualizado hoje</span>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl mb-4">
          <p class="text-gray-700"><span class="font-semibold">Resumo:</span><p id="iaResumo"> </p></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-gray-50 p-4 rounded-xl">
            <h3 class="text-md font-semibold text-gray-800 mb-2">Alertas</h3>
            <ul class="space-y-2" id="iaAlertas">
            </ul>
          </div>
          <div class="bg-gray-50 p-4 rounded-xl">
            <h3 class="text-md font-semibold text-gray-800 mb-2">Otimizações Sugeridas</h3>
            <ul class="space-y-2" id="iaSugestoes">
              
            </ul>
          </div>
        </div>
      </div>

      <!-- IoT Metrics Overview -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Consumo de Energia</p>
              <h3 class="text-2xl font-bold text-gray-800" id="energia">85 kWh</h3>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
              <i class="fas fa-bolt text-green-500"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Temperatura</p>
              <h3 class="text-2xl font-bold text-gray-800" id="temperatura">23.5°C</h3>

            </div>
            <div class="bg-blue-100 p-3 rounded-full">
              <i class="fas fa-thermometer-half text-blue-500"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Ocupação</p>
              <h3 class="text-2xl font-bold text-gray-800" id="ocupacao">68%</h3>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
              <i class="fas fa-users text-yellow-500"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Qualidade do Ar</p>
              <h3 class="text-2xl font-bold text-gray-800" id="ar">Boa</h3>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
              <i class="fas fa-wind text-purple-500"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Energy Consumption Chart -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
          <h3 class="text-white font-semibold">Consumo de Energia (Últimos 7 dias)</h3>

        </div>
        <div class="p-6">
          <div class="h-80">
            <canvas id="energyChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Temperature and Occupancy -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Temperature Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-primary px-6 py-4">
            <h3 class="text-white font-semibold">Temperatura (Hoje)</h3>
          </div>
          <div class="p-6">
            <div class="h-64">
              <canvas id="temperatureChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Occupancy Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-primary px-6 py-4">
            <h3 class="text-white font-semibold">Ocupação (Hoje)</h3>
          </div>
          <div class="p-6">
            <div class="h-64">
              <canvas id="occupancyChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Alerts -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
          <h3 class="text-white font-semibold">Alertas Recentes</h3>
        </div>
        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensagem</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200" id="tabelaProblema">
                
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

    
  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <script>
    const energia = +(Math.random() * 100).toFixed(2); // 0–100 kWh
    const temperatura = +(15 + Math.random() * 10).toFixed(1); // 18–32 °C
    const ocupacao = Math.floor(30 + Math.random() * 70); // 0–100 %
    const ppm = Math.floor(300 + Math.random() * 1700); // 300–2000 ppm
    function qualidadeDoAr(ppm) {
      if (ppm <= 700) return "Excelente";
      if (ppm <= 1000) return "Boa";
      if (ppm <= 1500) return "Moderada";
      if (ppm <= 2000) return "Ruim";
      if (ppm <= 5000) return "Muito ruim";
      return "Perigoso";
    }

    document.getElementById("energia").textContent = energia + " kWh";
    document.getElementById("temperatura").textContent = temperatura + " °C";
    document.getElementById("ocupacao").textContent = ocupacao + "%";
    document.getElementById("ar").textContent = qualidadeDoAr(ppm);
      
    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      const sidebar = document.querySelector('.fixed');
      sidebar.classList.toggle('-translate-x-full');
    });


    const metaKwH = [50, 50, 50, 50, 50, 50, 50];
    const consumeReal = [+(Math.random() * 100).toFixed(2), +(Math.random() * 100).toFixed(2), +(Math.random() * 100).toFixed(2), energia, +(Math.random() * 100).toFixed(2), +(Math.random() * 100).toFixed(2), +(Math.random() * 100).toFixed(2)]

    const temperaturaReal = [+(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), +(18 + Math.random() * 14).toFixed(1), ]
    const ocupacaoReal = [Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), Math.floor(30 + Math.random() * 70), ];

    // Energy Consumption Chart
    const energyCtx = document.getElementById('energyChart').getContext('2d');
    const energyChart = new Chart(energyCtx, {
      type: 'bar',
      data: {
        labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
        datasets: [{
            label: 'Consumo Real (kWh)',
            data: consumeReal,
            backgroundColor: '#00A9A5',
            borderRadius: 6
          },
          {
            label: 'Meta (kWh)',
            data: metaKwH,
            backgroundColor: 'rgba(0, 169, 165, 0.2)',
            borderRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Consumo (kWh)'
            }
          }
        }
      }
    });

    // Temperature Chart
    const tempCtx = document.getElementById('temperatureChart').getContext('2d');
    const tempChart = new Chart(tempCtx, {
      type: 'line',
      data: {
        labels: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'],
        datasets: [{
            label: 'Temperatura (°C)',
            data: temperaturaReal,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            pointBackgroundColor: '#3B82F6',
            tension: 0.4,
            fill: true
          },
          {
            label: 'Ideal',
            data: [22.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0],
            borderColor: '#9CA3AF',
            borderDash: [5, 5],
            borderWidth: 2,
            pointRadius: 0,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          y: {
            min: 15,
            max: 45,
            title: {
              display: true,
              text: 'Temperatura (°C)'
            }
          }
        }
      }
    });

    // Occupancy Chart
    const occCtx = document.getElementById('occupancyChart').getContext('2d');
    const occChart = new Chart(occCtx, {
      type: 'line',
      data: {
        labels: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'],
        datasets: [{
          label: 'Ocupação (%)',
          data: ocupacaoReal,
          borderColor: '#F59E0B',
          backgroundColor: 'rgba(245, 158, 11, 0.1)',
          borderWidth: 3,
          pointBackgroundColor: '#F59E0B',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            title: {
              display: true,
              text: 'Ocupação (%)'
            }
          }
        }
      }
    });
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
    async function aIAnalises() {
      const promptFinal = `
Analise os seguintes de um armazem:
- consumo total de energia da semana: ${consumeReal}
- consumo de energia atual: ${energia}
- temperatura hj entre 8 e 17hrs: ${temperaturaReal}
- temperatura agora: ${temperatura}
- ocupacao(pessoal no armazem) hoje em %:${ocupacaoReal}
- ocupacao atual: ${ocupacao}
- qualidade do ar em ppm: ${ppm}

Analise os dados abaixo e gere o resultado exatamente no formato indicado.
Nao use JSON, nao use blocos de codigo, nao use crases, nao use aspas duplas.
Use exatamente o modelo abaixo:

{
      "content": [
      {
        "nome": "Resumo",
        "dica": "Analise dos dados apresentados em um pequeno resumo"
      },
      {
        "nome": "Alertas de dispositivos",
        "alerta": [3 alertas strings de alertas baseados nos dados que voce tem]
      },
      {
        "nome": "Otimizaçoes sugeridas",
        "dica": [3 otimizacoes strings de otimizacao baseados nos dados que voce tem]
      },
      {
        "nome": "Alertas recentes",
        "data": [{
          "tipo": "Crítico"|"Alerta",
          "Mensagem": "",
          "Status":"Resolvido"
        },
        {
          "tipo": "Crítico"|"Alerta",
          "Mensagem": "",
          "Status":"Resolvido"
        },
        {
          "tipo": "Crítico"|"Alerta",
          "Mensagem": "",
          "Status":"Resolvido"
        }]
      }
      ]
}

`;
      console.log(promptFinal)
      const resultado = await enviarParaGemini(promptFinal);
      console.log(resultado);
      data = JSON.parse(resultado);
      var tabelaProblema="";
      data.content[3].data.forEach(element => {
        tabelaProblema += `<tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">${element.tipo}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${element.Mensagem}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">${element.Status}</span>
                  </td>
                </tr>`;
      });
      document.getElementById("tabelaProblema").innerHTML = tabelaProblema;
      document.getElementById("iaResumo").textContent = data.content[0].dica;
      var alertasDominion = "";
      data.content[1].alerta.forEach(element=> {
        alertasDominion += `<li class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                <span class="text-sm text-gray-700">${element}</span>
              </li>`;
      });
      document.getElementById("iaAlertas").innerHTML = alertasDominion;
      var sugestsDominion = "";
      data.content[2].dica.forEach(element=> {
        sugestsDominion += `
              <li class="flex items-start">
                <i class="fas fa-lightbulb text-green-500 mt-1 mr-2"></i>
                <span class="text-sm text-gray-700">${element}</span>
              </li>`;
      });
      document.getElementById("iaSugestoes").innerHTML = sugestsDominion;
    }
    window.onload = function () {
      aIAnalises();
    }

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
</body>

</html>