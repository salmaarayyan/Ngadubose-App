import Chart from 'chart.js/auto';

window.initAdminCharts = function(statsData, kategoriData) {
    const ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus) {
        new Chart(ctxStatus.getContext('2d'), {
            type: 'doughnut', 
            data: {
                labels: ['Diterima', 'Diverifikasi', 'Diproses', 'Selesai'],
                datasets: [{
                    data: statsData,
                    backgroundColor: ['#F59E0B', '#A855F7', '#3B82F6', '#10B981'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    const ctxKategori = document.getElementById('chartKategori');
    if (ctxKategori) {
        new Chart(ctxKategori.getContext('2d'), {
            type: 'bar', 
            data: {
                labels: ['Gratifikasi', 'Benturan Kepentingan', 'Korupsi', 'Pelanggaran Aturan', 'Lainnya'],
                datasets: [{
                    label: 'Jumlah Aduan',
                    data: kategoriData,
                    backgroundColor: '#002B5B',
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 } 
                    }
                },
                plugins: {
                    legend: { display: false } 
                }
            }
        });
    }
}