import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('reportChart');
    if (!chartCanvas) return;

    const chartDataAttribute = chartCanvas.dataset.chartData;
    if (!chartDataAttribute) return;

    try {
        const chartData = JSON.parse(chartDataAttribute);
        const chartType = chartCanvas.dataset.chartType;

        let labels = [];
        let data = [];

        if (chartType === 'bulanan') {
            // --- Logika untuk Diagram Bulanan (detail harian) ---
            const year = parseInt(chartCanvas.dataset.year);
            const month = parseInt(chartCanvas.dataset.month);
            const daysInMonth = new Date(year, month, 0).getDate();
            
            labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
            data = Array(daysInMonth).fill(0);
            
            chartData.forEach(item => {
                const index = item.hari - 1;
                data[index] = item.total;
            });

        } else if (chartType === 'tahunan') {
            // --- Logika untuk Diagram Tahunan (perbandingan bulan) ---
            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            data = Array(12).fill(0);

            chartData.forEach(item => {
                const index = item.bulan - 1; // bulan 1 (Januari) -> index 0
                data[index] = item.total;
            });
        }
        
        // Inisialisasi Chart.js
        new Chart(chartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: data,
                    backgroundColor: 'rgba(67, 97, 238, 0.6)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                }]
            },
            options: { /* ... opsi chart Anda ... */ }
        });

    } catch (e) {
        console.error("Gagal membuat diagram:", e);
    }
});
