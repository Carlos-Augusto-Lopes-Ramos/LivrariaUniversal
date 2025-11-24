

class DashboardManager {
    constructor() {
        this.charts = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeCharts();
        this.setupRealTimeUpdates();
    }

    setupEventListeners() {
        const aiBtn = document.getElementById("aiAnalysisBtn");
        const closeBtn = document.getElementById("closeAiAnalysis");

        if (aiBtn) {
            aiBtn.addEventListener("click", () => this.showAIAnalysis());
        }
        if (closeBtn) {
            closeBtn.addEventListener("click", () => this.hideAIAnalysis());
        }
    }

    // ============================================================
    //  ANÁLISE COM IA
    // ============================================================

    async showAIAnalysis() {
        const div = id => document.getElementById(id);

        const aiSection = div("aiAnalysisSection");
        const aiLoading = div("aiLoading");
        const aiResults = div("aiResults");

        aiSection.classList.remove("hidden");
        aiLoading.classList.remove("hidden");
        aiResults.classList.add("hidden");

        

        aiLoading.classList.add("hidden");
        aiResults.classList.remove("hidden");
    }


    hideAIAnalysis() {
        const aiSection = document.getElementById("aiAnalysisSection");
        aiSection.classList.add("hidden");
    }

    // ============================================================
    //  CHARTS
    // ============================================================

    initializeCharts() {
        this.createSalesChart();
        this.createCategoryChart();
        this.createRevenueChart();
    }

    createSalesChart() {
        const el = document.getElementById("salesChart");
        if (!el) return;

        this.charts.sales = new Chart(el, {
            type: "line",
            data: {
                labels: dashboardData.salesByMonth.labels,
                datasets: [
                    {
                        label: "Vendas",
                        data: dashboardData.salesByMonth.values,
                        borderColor: "#00A9A5",
                        tension: 0.4
                    }
                ]
            }
        });
    }

    createCategoryChart() {
        const el = document.getElementById("categoryChart");
        if (!el) return;

        this.charts.category = new Chart(el, {
            type: "bar",
            data: {
                labels: dashboardData.booksByCategory.labels,
                datasets: [
                    {
                        label: "Livros por categoria",
                        data: dashboardData.booksByCategory.values,
                        backgroundColor: "#0077cc"
                    }
                ]
            }
        });
    }

    createRevenueChart() {
        const el = document.getElementById("revenueChart");
        if (!el) return;

        this.charts.revenue = new Chart(el, {
            type: "line",
            data: {
                labels: ["Q1", "Q2", "Q3", "Q4"],
                datasets: [
                    {
                        label: "Receita",
                        data: [12000, 15000, 18000, 22000],
                        borderColor: "#22bb33"
                    }
                ]
            }
        });
    }

    // ============================================================
    //  ATUALIZAÇÕES EM TEMPO REAL
    // ============================================================

    setupRealTimeUpdates() {
        setInterval(() => this.updateDashboardData(), 30000);
    }

    async updateDashboardData() {
        console.log("Simulação de atualização...");
    }
}

// ============================================================
//  INICIALIZAÇÃO GLOBAL
// ============================================================

document.addEventListener("DOMContentLoaded", () => {
    window.dashboardManager = new DashboardManager();
});
