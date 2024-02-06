<?php
// Подключение к базе данных с использованием PDO
$pdo = new PDO('mysql:host=localhost;dbname=test-delta', 'root', '');

// Создание таблицы mone_turnover при ее отсутствии
$query = "CREATE TABLE IF NOT EXISTS mone_turnover (
    id INT AUTO_INCREMENT PRIMARY KEY,
    revenue_today DECIMAL(10, 2),
    revenue_yesterday DECIMAL(10, 2),
    revenue_week DECIMAL(10, 2),
    cash_today DECIMAL(10, 2),
    cash_yesterday DECIMAL(10, 2),
    cash_week DECIMAL(10, 2),
    non_cash_today DECIMAL(10, 2),
    non_cash_yesterday DECIMAL(10, 2),
    non_cash_week DECIMAL(10, 2)
)";
$pdo->exec($query);

// Внесение тестовых данных, если таблица была создана или уже существует
$query = "SELECT COUNT(*) as count FROM mone_turnover";
$statement = $pdo->query($query);
$row = $statement->fetch(PDO::FETCH_ASSOC);

if ($row['count'] < 10) {
    for ($i = 1; $i <= 10; $i++) {
        $revenue_today = rand(100, 1000);
        $revenue_yesterday = rand(100, 1000);
        $revenue_week = rand(100, 1000);
        $cash_today = rand(100, 1000);
        $cash_yesterday = rand(100, 1000);
        $cash_week = rand(100, 1000);
        $non_cash_today = rand(100, 1000);
        $non_cash_yesterday = rand(100, 1000);
        $non_cash_week = rand(100, 1000);

        $query = "INSERT INTO mone_turnover (revenue_today, revenue_yesterday, revenue_week, cash_today, cash_yesterday, cash_week, non_cash_today, non_cash_yesterday, non_cash_week) 
                  VALUES ($revenue_today, $revenue_yesterday, $revenue_week, $cash_today, $cash_yesterday, $cash_week, $non_cash_today, $non_cash_yesterday, $non_cash_week)";
        $pdo->exec($query);
    }
}

// Запрос к базе данных
$query = "SELECT * FROM mone_turnover";
$statement = $pdo->query($query);
$row = $statement->fetch(PDO::FETCH_ASSOC);

// Формирование данных для ответа на AJAX запрос в формате JSON
$data = [
    'revenue' => [
        'today' => $row['revenue_today'],
        'yesterday' => $row['revenue_yesterday'],
        'week' => $row['revenue_week']
    ],
    'cash' => [
        'today' => $row['cash_today'],
        'yesterday' => $row['cash_yesterday'],
        'week' => $row['cash_week']
    ],
    'non_cash' => [
        'today' => $row['non_cash_today'],
        'yesterday' => $row['non_cash_yesterday'],
        'week' => $row['non_cash_week']
    ]
];

header('Content-Type: application/json');
echo json_encode($data);
?>