
<!-- VLibras Accessibility -->
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

<!-- Header Section -->
<header class="bg-white shadow-lg sticky top-0 z-50">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between py-4">
      
      <!-- Logo -->
      <div class="flex items-center">
        <a href="./index.php" class="flex items-center space-x-3 group">
          <div class="bg-primary rounded-xl p-3 group-hover:bg-primary-hover transition-colors duration-300">
            <i class="fas fa-book text-white text-2xl"></i>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors duration-300">Livraria Universal</h1>
            <p class="text-sm text-gray-500">Sua livraria online</p>
          </div>
        </a>
      </div>

      <!-- Search Bar -->
      <div class="flex-1 max-w-2xl mx-8 hidden md:block">
        <form action="./catalogo.php" method="GET" class="relative">
          <div class="relative">
            <input 
              type="text" 
              placeholder="Buscar livros, autores, categorias..." 
              name="search" 
              class="w-full px-6 py-3 pl-12 pr-4 border border-gray-300 rounded-full focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all duration-300 hover:border-primary/50"
            >
            <button type="submit" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-300">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- User Actions -->
      <div class="flex items-center space-x-4">
        
        <!-- Mobile Search Button -->
        <button class="md:hidden p-2 text-gray-600 hover:text-primary transition-colors duration-300" onclick="toggleMobileSearch()">
          <i class="fas fa-search text-xl"></i>
        </button>

        <!-- User Menu -->
        <?php if (isset($_SESSION['email'])): ?>
          <div class="flex items-center space-x-3">
            <!-- User Info -->
            <div class="hidden sm:block text-right">
              <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
              <p class="text-xs text-gray-500">
                <?php echo $_SESSION['admin'] == 1 ? 'Administrador' : 'Usuário'; ?>
              </p>
            </div>
            
            <!-- Dashboard/Library Button -->
            <?php if ($_SESSION['admin'] == 1): ?>
              <a href="./admin/index.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors duration-300 flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i>
                <span class="hidden sm:inline">Dashboard</span>
              </a>
            <?php else: ?>
              <a href="./library.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors duration-300 flex items-center">
                <i class="fas fa-book mr-2"></i>
                <span class="hidden sm:inline">Minha Biblioteca</span>
              </a>
            <?php endif; ?>
            
            <!-- Logout Button -->
            <a href="./logout.php" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center">
              <i class="fas fa-sign-out-alt mr-2"></i>
              <span class="hidden sm:inline">Sair</span>
            </a>
          </div>
        <?php else: ?>
          <!-- Login/Register Buttons -->
          <div class="flex items-center space-x-3">
            <a href="./login.php" class="text-gray-600 hover:text-primary transition-colors duration-300 px-4 py-2 rounded-lg hover:bg-gray-100">
              <i class="fas fa-sign-in-alt mr-2"></i>
              <span class="hidden sm:inline">Login</span>
            </a>
            <a href="./cadastro.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors duration-300 flex items-center">
              <i class="fas fa-user-plus mr-2"></i>
              <span class="hidden sm:inline">Cadastro</span>
            </a>
          </div>
        <?php endif; ?>

        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 text-gray-600 hover:text-primary transition-colors duration-300" onclick="toggleMobileMenu()">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Search Bar -->
    <div id="mobileSearch" class="hidden md:hidden pb-4">
      <form action="./catalogo.php" method="GET" class="relative">
        <div class="relative">
          <input 
            type="text" 
            placeholder="Buscar livros, autores, categorias..." 
            name="search" 
            class="w-full px-6 py-3 pl-12 pr-4 border border-gray-300 rounded-full focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all duration-300"
          >
          <button type="submit" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-300">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden pb-4 border-t border-gray-200 pt-4">
      <div class="space-y-2">
        <a href="./catalogo.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
          <i class="fas fa-book mr-3"></i>Catálogo de Livros
        </a>
        
        <?php if (isset($_SESSION['email'])): ?>
          <div class="px-4 py-2 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p class="text-xs text-gray-500">
              <?php echo $_SESSION['admin'] == 1 ? 'Administrador' : 'Usuário'; ?>
            </p>
          </div>
          
          <?php if ($_SESSION['admin'] == 1): ?>
            <a href="./admin/index.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
              <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
          <?php else: ?>
            <a href="./library.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
              <i class="fas fa-book mr-3"></i>Minha Biblioteca
            </a>
          <?php endif; ?>
          
          <a href="./logout.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
            <i class="fas fa-sign-out-alt mr-3"></i>Sair
          </a>
        <?php else: ?>
          <a href="./login.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
            <i class="fas fa-sign-in-alt mr-3"></i>Login
          </a>
          <a href="./cadastro.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-300">
            <i class="fas fa-user-plus mr-3"></i>Cadastro
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<script>
function toggleMobileSearch() {
  const mobileSearch = document.getElementById('mobileSearch');
  const mobileMenu = document.getElementById('mobileMenu');
  
  mobileSearch.classList.toggle('hidden');
  mobileMenu.classList.add('hidden');
}

function toggleMobileMenu() {
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileSearch = document.getElementById('mobileSearch');
  
  mobileMenu.classList.toggle('hidden');
  mobileSearch.classList.add('hidden');
}
</script>