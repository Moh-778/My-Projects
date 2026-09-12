<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
<<<<<<< HEAD
require_once __DIR__ . '/../mailer.php'; 
require_once __DIR__ . '/../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ?page=forgot");
    exit;
}

$language = $_SESSION['lang'] ?? 'en';

$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    $_SESSION['error_message'] = 'Please enter your email address.';
=======
require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ?page=forgot");
    exit;
}

$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    $_SESSION['error_message'] = 'Please enter your email address.';
    header("Location: ?page=forgot");
    exit;
}

// Check if user exists
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error_message'] = 'No account found with that email.';
    header("Location: ?page=forgot");
    exit;
}

// Create reset token
$token = bin2hex(random_bytes(32));
$expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Store token
$stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at, expires_at) VALUES (?, ?, NOW(), ?)");
$stmt->execute([$user['id'], $token, $expires_at]);

// Build reset link
$reset_link = BASE_URL . "/?page=reset_password&token=" . urlencode($token);

// Send email via PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ksu19577@gmail.com'; // ✅ Replace with your Gmail
    $mail->Password   = 'shzdjhztzzigabna';   // ✅ Replace with your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('ksu19577@gmail.com', 'King saud university Events');
    $mail->addAddress($email, $user['name']);
    $mail->isHTML(true);
    $mail->Subject = '🔑 Password Reset Request';
    $mail->Body    = "
        <h2>Hello {$user['name']},</h2>
        <p>You requested to reset your password.</p>
        <p>Click the link below to set a new password:</p>
        <p><a href='{$reset_link}' target='_blank'>Reset Password</a></p>
        <p>This link will expire in 1 hour.</p>
        <hr>
        <p>If you didn’t request this, please ignore this email.</p>
    ";

    $mail->send();

    $_SESSION['success_message'] = '📧 Password reset link has been sent to your email.';
    header("Location: ?page=forgot");
    exit;

} catch (Exception $e) {
    $_SESSION['error_message'] = "Mailer Error: {$mail->ErrorInfo}";
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
    header("Location: ?page=forgot");
    exit;
}

// Check if user exists
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE email = ?");$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error_message'] = 'No account found with that email.';
    header("Location: ?page=forgot");
    exit;
}

// Create reset token
$token = bin2hex(random_bytes(32));
$expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Store token
$stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at, expires_at) VALUES (?, ?, NOW(), ?)");
$stmt->execute([$user['id'], $token, $expires_at]);

// Build reset link
$reset_link = BASE_URL . "/?page=reset_password&token=" . urlencode($token);

// Send email via PHPMailer
$mail = new PHPMailer(true);
$email_sent = sendResetPassword(
    $user['email'],    // 1. البريد الإلكتروني (الوسيط $to)
    $user['name'],     // 2. اسم المستخدم (الوسيط $user_name)
    $reset_link,       // 3. رابط إعادة التعيين (الوسيط $reset_link)
    $language          // 4. اللغة (الوسيط $language)
);
    if ($email_sent) {
         $_SESSION['success_message'] = $_SESSION['lang'] == 'ar' ? '  لقد تم  ارسال البريد الإلكتروني 📧 ' : ' 📧 Password reset link has been sent to your email.';
            header("Location: ?page=forgot");
            exit;

    }  else {
    $_SESSION['error_message'] = "Mailer Error: {$mail->ErrorInfo}";
    header("Location: ?page=forgot");
    exit;
}

   

