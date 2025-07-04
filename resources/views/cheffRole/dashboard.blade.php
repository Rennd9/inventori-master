@extends('template')

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main Chart
    const mainCtx = document.getElementById('mainChart').getContext('2d');
    const mainChart = new Chart(mainCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Sales',
                data: [12500, 19000, 17000, 22000, 25000, 21000, 28000],
                backgroundColor: '#8B5CF6',
                borderColor: '#000',
                borderWidth: 2
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
                    beginAtZero: true,
                    grid: {
                        drawBorder: true,
                        color: '#000',
                        borderColor: '#000',
                        borderWidth: 2
                    },
                    ticks: {
                        color: '#000',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: true,
                        color: '#000',
                        borderColor: '#000',
                        borderWidth: 2
                    },
                    ticks: {
                        color: '#000',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Direct', 'Social', 'Referral', 'Organic'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#F59E0B',
                    '#3B82F6',
                    '#8B5CF6',
                    '#10B981'
                ],
                borderColor: '#000',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000',
                        font: {
                            weight: 'bold'
                        },
                        padding: 20
                    }
                }
            },
            cutout: '70%'
        }
    });
});

// Sidebar navigation
document.querySelectorAll('.sidebar-item').forEach(item => {
    item.addEventListener('click', function() {
        // Remove active class from all items
        document.querySelectorAll('.sidebar-item').forEach(i => {
            i.classList.remove('active');
        });
        // Add active class to clicked item
        this.classList.add('active');
    });
});
</script>
@endsection