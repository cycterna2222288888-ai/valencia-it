<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$to      = 'info@valencia-it.am'; // заменить на нужный email
$name    = trim($_POST['name']    ?? '');
$surname = trim($_POST['surname'] ?? '');
$email   = trim($_POST['email']   ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// Валидация
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Заполните обязательные поля']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Неверный email']);
    exit;
}

// Защита от инъекций в заголовки
$name    = str_replace(["\r", "\n"], '', $name);
$surname = str_replace(["\r", "\n"], '', $surname);
$email   = str_replace(["\r", "\n"], '', $email);
$service = str_replace(["\r", "\n"], '', $service);

$subject = 'Новая заявка с сайта Valencia IT — ' . $name . ' ' . $surname;

$body  = "Имя: $name $surname\n";
$body .= "Email: $email\n";
if ($service) $body .= "Услуга: $service\n";
$body .= "\nСообщение:\n$message\n";
$body .= "\n---\nОтправлено с valencia-it.am";

$headers  = "From: noreply@valencia-it.am\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Ошибка отправки']);
}
