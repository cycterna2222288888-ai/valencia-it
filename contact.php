<?php
if(!defined('__ROOTACCESS__')){die('Restricted access!');}
header('Content-Type: application/json; charset=utf-8');
#CORS
header('Access-Control-Allow-Origin: https://localhost'); // Заменить на домен
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
#Check headers
if($_SERVER['REQUEST_METHOD']!=='POST')
{
    http_response_code(405);
    die(json_encode(['success' => false, 'error' => 'Method not allowed']));
}
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!=='xmlhttprequest')
{
    http_response_code(403);
    die(json_encode(['success' => false, 'error' => 'HTTP 403 Forbidden']));
}
#Send form
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if(isset($_POST['send']))
{
	# Согласие на обработку ПДн
	if(isset($_POST['consent-pdn']))
	{
		$_POST['consent-pdn']=1;
	}
	else
	{
		http_response_code(400);
		die(json_encode(['success' => false, 'error' => 'Нет согласия на обработку ПДн']));
	}
	# Очистка от пробелов
	$_POST['name']		= trim($_POST['name'] ?? '');
	$_POST['surname']	= trim($_POST['surname'] ?? '');
	$_POST['email']		= trim($_POST['email'] ?? '');
	$_POST['service']	= trim($_POST['service'] ?? '');
	$_POST['message']	= trim($_POST['message'] ?? '');
	# Согласие на рекламную рассылку
	isset($_POST['consent-ad']) ? $_POST['consent-ad']=1 : $_POST['consent-ad']=0;
	# ФИО
	if(empty($_POST['name']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['service']))
	{
		http_response_code(400);
		die(json_encode(['success' => false, 'error' => 'Заполните обязательные поля']));
	}
	# Почта
	if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	{
		http_response_code(400);
		die(json_encode(['success' => false, 'error' => 'Неправильный адрес E-mail']));
	}
	# Дата и время
	$timestamp=date('Y.m.d H:i:s');

	# Формируем уведомление на почту
	# HTML
	$htm='<h1>💬 Сообщение '.preg_replace('/[^0-9]/','',$timestamp).'</h1>'."\n"
		."\n"
		.'<h2>Статус согласий:</h2>'."\n"
		.'<p><b>Согласие на обработку ПДн</b> - '.($_POST['consent-pdn']==1 ? '✅' : '❌').'</p>'."\n"
		.'<p><b>Согласие на рассылку</b> - '.($_POST['consent-ad']==1 ? '✅' : '❌').'</p>'."\n"
		."\n"
		.'<h2>👤 Данные клиента:</h2>'."\n"
		.'<p><b>Фамилия:</b> '.$_POST['surname'].'</p>'."\n"
		.'<p><b>Имя:</b> '.$_POST['name'].'</p>'."\n"
		.'<p><b>E-mail:</b> <a href="mailto:'.$_POST['email'].'">'.$_POST['email'].'</a></p>'."\n"
		.'<p><b>Услуга:</b> '.$_POST['service'].'</p>'."\n"
		.'<p><b>Сообщение:</b> '.$_POST['message'].'</p>'."\n"
		."\n"
		.'<h2>🖥️ Технические данные:</h2>'."\n"
		.'<p><b>Дата/время:</b> '.$timestamp.'</p>'."\n"
		.'<p><b>Страница:</b> '.$_SERVER['HTTP_REFERER'].'</p>'."\n"
		.'<p><b>IP-адрес:</b> '.(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']).'</p>'."\n"
		.'<p><b>Браузер:</b> '.$_SERVER['HTTP_USER_AGENT'].'</p>'."\n"
		.'<p><b>Версия Согласия на обработку ПДн:</b> Редакция №&nbsp;1 от 01.09.2025&nbsp;г.'.'</p>'."\n"
		.'<p><b>Версия Политики конфиденциальности и обработки ПДн:</b> Редакция №&nbsp;1 от 01.09.2025&nbsp;г.'.'</p>'."\n"
	;
	# Plain text
	$msg='💬 Сообщение '.preg_replace('/[^0-9]/','',$timestamp)."\n"
		."\n"
		.'Статус согласий:'."\n"
		.'Согласие на обработку ПДн - '.($_POST['consent-pdn']==1 ? '✅' : '❌')."\n"
		.'Согласие на рассылку - '.($_POST['consent-ad']==1 ? '✅' : '❌')."\n"
		."\n"
		.'👤 Данные клиента:'."\n"
		.'Фамилия: '.$_POST['surname']."\n"
		.'Имя: '.$_POST['name']."\n"
		.'E-mail: '.$_POST['email']."\n"
		.'Услуга: '.$_POST['service']."\n"
		.'Сообщение: '.$_POST['message']."\n"
		."\n"
		.'🖥️ Технические данные:'."\n"
		.'Дата/время: '.$timestamp."\n"
		.'Страница: '.$_SERVER['HTTP_REFERER']."\n"
		.'IP-адрес: '.(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'])."\n"
		.'Браузер: '.$_SERVER['HTTP_USER_AGENT']."\n"
		.'Версия Согласия на обработку ПДн: Редакция № 1 от 01.09.2025 г.'."\n"
		.'Версия Политики конфиденциальности и обработки ПДн: Редакция № 1 от 01.09.2025 г.'."\n"
	;
	
	# Формируем лог
	/*
	$log_pdn=
	[
		'datetime' => $timestamp,
		'order' => preg_replace('/[^0-9]/','',$timestamp),
		'consent-pdn' => $_POST['consent-pdn']==1 ? 'Да' : 'Нет',
		'consent-ad' => $_POST['consent-ad']==1 ? 'Да' : 'Нет',
		'fio' => !empty($_POST['name']) ? 'Да' : 'Нет',
		'email' => !empty($_POST['email']) ? 'Да' : 'Нет',
		'tel' => !empty($_POST['tel']) ? 'Да' : 'Нет',
		'page' => $_SERVER['HTTP_REFERER'],
		'ip' => !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
		'ua' => $_SERVER['HTTP_USER_AGENT'],
		'consent_v' => 'Редакия № 1 от 01.09.2025 г.',
		'policy_v' => 'Редакия № 1 от 01.09.2025 г.'
	];
	
	if(filesize($root_dir.'/pdn_log.txt')>0)
	{
		@file_put_contents($root_dir.'/pdn_log.txt',
			",\n".json_encode($log_pdn,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL,
			FILE_APPEND
		);
	}
	else
	{
		@file_put_contents($root_dir.'/pdn_log.txt',
			json_encode($log_pdn,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL,
			FILE_APPEND
		);
	}
	*/

	#Отправка уведомления на почту
	include('PHPMailer/src/PHPMailer.php');
	include('PHPMailer/src/SMTP.php');
	include('PHPMailer/src/Exception.php');
	$mail = new PHPMailer(true);
	$mail->setLanguage('ru','PHPMailer/language/');
	try
	{
		//Server settings
		# $mail->SMTPDebug = SMTP::DEBUG_SERVER;			//Enable verbose debug output
		$mail->isSMTP();									//Send using SMTP
		$mail->Host			= 'localhost';					//Set the SMTP server to send through
		$mail->SMTPAuth		= true;							//Enable SMTP authentication
		$mail->Username		= 'admin@localhost';			//SMTP username
		$mail->Password		= 'password';					//SMTP password
		$mail->SMTPSecure	= PHPMailer::ENCRYPTION_SMTPS;	//Enable implicit TLS encryption
		$mail->Port			= 465;//465|587					//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		$mail->CharSet		= 'UTF-8';
		//Recipients
		$mail->setFrom('admin@localhost','💬 Сообщение с сайта localhost');
		$mail->addAddress('info@localhost','Company Name');
		$mail->addReplyTo($_POST['email'],$_POST['surname'].' '.$_POST['name']);
		// Content
		$mail->isHTML(true);								// Set email format to HTML
		$mail->Subject = '💬 Сообщение с сайта';
		$mail->Body    = $htm;
		$mail->AltBody = $msg;
		$mail->send();
	}
	catch(Exception $e)
	{
		http_response_code(500);
		die(json_encode(['success' => false, 'error' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]));
	}

	#Отправка уведомления в Telegram
	/*
	include('system'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'telegram.php');
	$tg=new telegram_bot('');
	$tg_msg='<b>💬 Сообщение с сайта</b>'."\n";

	# $tg->reply('sendMessage',['chat_id'=>'nnnnnnnnn', 'text'=>$tg_msg,'parse_mode'=>'HTML']);#Developer
	*/

	die(json_encode(['success' => true]));
}
else
{
	die(json_encode(['success' => false]));
}
?>