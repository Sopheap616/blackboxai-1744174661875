<?php
require_once '../config/database.php';
include '../includes/header.php';

$conn = getDBConnection();

// Default date range (current month)
$startDate = date('Y-m-01');
$endDate = date('Y-m-t');
$currency = 'all';

// Process filters if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? $startDate;
    $endDate = $_POST['end_date'] ?? $endDate;
    $currency = $_POST['currency'] ?? 'all';
}

// Get summary data
$summaryQuery = "SELECT 
    SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE 0 END) AS total_income,
    SUM(CASE WHEN c.type = 'expense' THEN t.amount ELSE 0 END) AS total_expense,
    SUM(t.amount) AS net_balance
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ? 
    AND t.transaction_date BETWEEN ? AND ?";

if ($currency !== 'all') {
    $summaryQuery .= " AND t.currency_id = ?";
    $stmt = $conn->prepare($summaryQuery);
    $stmt->bind_param("issi", $_SESSION['user_id'], $startDate, $endDate, $currency);
} else {
    $stmt = $conn->prepare($summaryQuery);
    $stmt->bind_param("iss", $_SESSION['user_id'], $startDate, $endDate);
}

$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Financial Reports</h2>
    
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="<?= $startDate ?>" 
                    class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" value="<?= $endDate ?>" 
                    class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Currency</label>
                <select name="currency" class="w-full p-2 border rounded">
                    <option value="all" <?= $currency === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="1" <?= $currency === '1' ? 'selected' : '' ?>>USD</option>
                    <option value="2" <?= $currency === '2' ? 'selected' : '' ?>>KHR</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Total Income</h3>
            <p class="text-2xl font-bold text-green-600">
                $<?= number_format($summary['total_income'] ?? 0, 2) ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Total Expenses</h3>
            <p class="text-2xl font-bold text-red-600">
                $<?= number_format(abs($summary['total_expense'] ?? 0), 2) ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Net Balance</h3>
            <p class="text-2xl font-bold <?= ($summary['net_balance'] ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' ?>">
                $<?= number_format($summary['net_balance'] ?? 0, 2) ?>
            </p>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-4">Category Breakdown</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium mb-2">Income</h4>
                <div class="space-y-2">
                    <?php
                    $incomeQuery = "SELECT c.name, SUM(t.amount) AS total
                                  FROM transactions t
                                  JOIN categories c ON t.category_id = c.id
                                  WHERE t.user_id = ?
                                  AND c.type = 'income'
                                  AND t.transaction_date BETWEEN ? AND ?";
                    if ($currency !== 'all') {
                        $incomeQuery .= " AND t.currency_id = ?";
                        $stmt = $conn->prepare($incomeQuery);
                        $stmt->bind_param("issi", $_SESSION['user_id'], $startDate, $endDate, $currency);
                    } else {
                        $stmt = $conn->prepare($incomeQuery);
                        $stmt->bind_param("iss", $_SESSION['user_id'], $startDate, $endDate);
                    }
                    $stmt->execute();
                    $incomeCategories = $stmt->get_result();
                    
                    while ($row = $incomeCategories->fetch_assoc()): ?>
                        <div class="flex justify-between">
                            <span><?= $row['name'] ?></span>
                            <span class="font-medium text-green-600">
                                $<?= number_format($row['total'], 2) ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div>
                <h4 class="font-medium mb-2">Expenses</h4>
                <div class="space-y-2">
                    <?php
                    $expenseQuery = "SELECT c.name, SUM(t.amount) AS total
                                   FROM transactions t
                                   JOIN categories c ON t.category_id = c.id
                                   WHERE t.user_id = ?
                                   AND c.type = 'expense'
                                   AND t.transaction_date BETWEEN ? AND ?";
                    if ($currency !== 'all') {
                        $expenseQuery .= " AND t.currency_id = ?";
                        $stmt = $conn->prepare($expenseQuery);
                        $stmt->bind_param("issi", $_SESSION['user_id'], $startDate, $endDate, $currency);
                    } else {
                        $stmt = $conn->prepare($expenseQuery);
                        $stmt->bind_param("iss", $_SESSION['user_id'], $startDate, $endDate);
                    }
                    $stmt->execute();
                    $expenseCategories = $stmt->get_result();
                    
                    while ($row = $expenseCategories->fetch_assoc()): ?>
                        <div class="flex justify-between">
                            <span><?= $row['name'] ?></span>
                            <span class="font-medium text-red-600">
                                $<?= number_format(abs($row['total']), 2) ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Prepare chart data
$chartData = [
    'incomeExpense' => [
        'labels' => ['Income', 'Expenses', 'Net'],
        'data' => [
            $summary['total_income'] ?? 0,
            abs($summary['total_expense'] ?? 0),
            $summary['net_balance'] ?? 0
        ]
    ],
    'expenseCategories' => [
        'labels' => [],
        'data' => []
    ]
];

// Get expense categories for pie chart
$expenseQuery = "SELECT c.name, SUM(t.amount) AS total
               FROM transactions t
               JOIN categories c ON t.category_id = c.id
               WHERE t.user_id = ?
               AND c.type = 'expense'
               AND t.transaction_date BETWEEN ? AND ?";
if ($currency !== 'all') {
    $expenseQuery .= " AND t.currency_id = ?";
    $stmt = $conn->prepare($expenseQuery);
    $stmt->bind_param("issi", $_SESSION['user_id'], $startDate, $endDate, $currency);
} else {
    $stmt = $conn->prepare($expenseQuery);
    $stmt->bind_param("iss", $_SESSION['user_id'], $startDate, $endDate);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $chartData['expenseCategories']['labels'][] = $row['name'];
    $chartData['expenseCategories']['data'][] = abs($row['total']);
}
?>

<?php include '../includes/footer.php'; ?>
<script>
    const chartData = <?= json_encode($chartData) ?>;
</script>
<script src="../assets/js/reports.js"></script>
