<?php
// Use Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Function to send email
function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // 🔧 SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ksu19577@gmail.com';
        $mail->Password   = 'shzdjhztzzigabna';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // 📧 Sender and recipient
        $mail->setFrom('ksu19577@gmail.com', 'King Saud University Events');
        $mail->addAddress($to);

        // 📝 Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // ✅ Send it
        $mail->send();
        error_log("Email sent successfully to: " . $to);
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// ✅ Function to send booking confirmation
<<<<<<< HEAD
function sendBookingConfirmation($user_email, $user_name, $event_title, $event_date,$event_end_at, $event_location, $language = 'en') {
=======
function sendBookingConfirmation($user_email, $user_name, $event_title, $event_date, $event_location, $language = 'en') {
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
    if ($language == 'ar') {
        $subject = 'تأكيد حجز الفعالية';
        $body = "
            <div style='font-family: Arial, sans-serif; direction: rtl; text-align: right;'>
                <h2 style='color: #2c3e50;'>تأكيد الحجز</h2>
                <p>مرحباً <strong>{$user_name}</strong>،</p>
                <p>تم تأكيد حجزك في الفعالية بنجاح.</p>
                <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <h3 style='color: #27ae60; margin-top: 0;'>تفاصيل الفعالية:</h3>
                    <p><strong>اسم الفعالية:</strong> {$event_title}</p>
                    <p><strong>التاريخ:</strong> {$event_date}</p>
<<<<<<< HEAD
                    <p><strong>تاريخ الانتهاء:</strong> {$event_end_at}</p>
=======
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
                    <p><strong>المكان:</strong> {$event_location}</p>
                </div>
                <p>شكراً لاستخدامك نظام الفعاليات الجامعية.</p>
                <hr>
                <p style='color: #7f8c8d; font-size: 12px;'>هذا البريد الإلكتروني مرسل تلقائياً، يرجى عدم الرد عليه.</p>
            </div>
        ";
    } else {
        $subject = 'Event Booking Confirmation';
        $body = "
            <div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #2c3e50;'>Booking Confirmation</h2>
                <p>Hello <strong>{$user_name}</strong>,</p>
                <p>Your event booking has been confirmed successfully.</p>
                <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <h3 style='color: #27ae60; margin-top: 0;'>Event Details:</h3>
                    <p><strong>Event Title:</strong> {$event_title}</p>
                    <p><strong>Date:</strong> {$event_date}</p>
<<<<<<< HEAD
                    <p><strong>End Date:</strong> {$event_end_at}</p>
=======
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
                    <p><strong>Location:</strong> {$event_location}</p>
                </div>
                <p>Thank you for using University Events System.</p>
                <hr>
                <p style='color: #7f8c8d; font-size: 12px;'>This is an automated email, please do not reply.</p>
            </div>
        ";
    }
    
    return sendMail($user_email, $subject, $body);
}
<<<<<<< HEAD


function sendResetPassword($to, $user_name, $reset_link, $language) { // ✅ الترتيب الصحيح
    
    // 1. تعريف القيم الافتراضية (اللغة الإنجليزية) أولاً لمنع تحذيرات Undefined variable
    $subject = '🔑 Password Reset Request';
    $body = "
        <div style='font-family: Arial, sans-serif;'>
            <h2 style='color: #2c3e50;'>Hello {$user_name},</h2>
            <p>You requested to reset your password.</p>
            <p><strong>Click the link below to set a new password:</strong></p>
            <p style='margin: 20px 0;'>
                <a href='{$reset_link}' target='_blank' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                    Reset Password
                </a>
            </p>
            <p>This link will expire in 1 hour.</p>
        </div>
    ";

    // 2. التجاوز (Override) للغة العربية إذا كانت مطلوبة
    if ($language == 'ar') {
        $subject = 'طلب إعادة تعيين كلمة المرور 🔑';
        $body = "
            <div style='font-family: Arial, sans-serif; text-align: right;' dir='rtl'>
                <h2 style='color: #2c3e50;'>مرحباً {$user_name},</h2>
                <p>لقد طلبت إعادة تعيين كلمة مرورك.</p>
                <p><strong>انقر على الرابط أدناه لتعيين كلمة مرور جديدة:</strong></p>
                <p style='margin: 20px 0;'>
                    <a href='{$reset_link}' target='_blank' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                        إعادة تعيين كلمة المرور
                    </a>
                </p>
                <p>يرجى ملاحظة أن هذا الرابط سينتهي مفعوله خلال ساعة واحدة.</p>
            </div>
        ";
    }

    // 3. استدعاء الدالة الرئيسية (السطر 106)
    return sendMail($to, $subject, $body); 
}
=======
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
?>