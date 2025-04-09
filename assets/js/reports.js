document.addEventListener('DOMContentLoaded', function() {
    if (!chartData) return;

    // Income vs Expense Bar Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
    if (incomeExpenseCtx) {
        new Chart(incomeExpenseCtx, {
            type: 'bar',
            data: {
                labels: chartData.incomeExpense.labels,
                datasets: [{
                    label: 'Amount',
                    data: chartData.incomeExpense.data,
                    backgroundColor: [
                        'rgba(22, 163, 74, 0.7)',
                        'rgba(220, 38, 38, 0.7)',
                        'rgba(37, 99, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(22, 163, 74, 1)',
                        'rgba(220, 38, 38, 1)',
                        'rgba(37, 99, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Expense Pie Chart
    const expensePieCtx = document.getElementById('expensePieChart');
    if (expensePieCtx) {
        new Chart(expensePieCtx, {
            type: 'pie',
            data: {
                labels: chartData.expenseCategories.labels,
                datasets: [{
                    data: chartData.expenseCategories.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    }
});
