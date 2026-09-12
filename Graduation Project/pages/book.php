<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../mailer.php'; // Include the mailer

$user = current_user();
<<<<<<< HEAD

// ----------------------------------------
// 1. التحقق من صلاحية المستخدم (طالب فقط)
// ----------------------------------------
if (!$user || $user['role_id'] != 3) {   // only students
    $_SESSION['error_message'] = ($lang == 'ar')
=======
if (!$user || $user['role_id'] != 3) {   // only students
    $_SESSION['error_message'] = ($_SESSION['lang'] ?? 'en') == 'ar'
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
        ? 'الرجاء تسجيل الدخول كطالب أولاً.'
        : 'Please log in as a student first.';
    header("Location: ?page=login");
    exit;
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
<<<<<<< HEAD
    $_SESSION['error_message'] = ($lang == 'ar')
        ? 'لم يتم اختيار فعالية.'
=======
    $_SESSION['error_message'] = ($_SESSION['lang'] ?? 'en') == 'ar' 
        ? 'لم يتم اختيار فعالية.' 
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
        : 'No event selected.';
    header("Location: ?page=events");
    exit;
}

<<<<<<< HEAD
// ----------------------------------------
// 2. جلب تفاصيل الفعالية
// ----------------------------------------
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
=======
// Check if already booked
$stmt = $pdo->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user['id'], $event_id]);
if ($stmt->fetch()) {
    $_SESSION['error_message'] = ($_SESSION['lang'] ?? 'en') == 'ar'
        ? 'لقد قمت بحجز هذه الفعالية مسبقاً.'
        : 'You have already booked this event.';
    
    // 💡 تعديل التوجيه هنا أيضاً لـ my_bookings
    header("Location: ?page=my_bookings"); 
    exit;
}

// Get event info first
$stmt = $pdo->prepare("SELECT title, date, location FROM events WHERE id = ?");
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['error_message'] = ($lang == 'ar')
        ? 'الفعالية غير موجودة.'
        : 'Event not found.';
    header("Location: ?page=events");
    exit;
}

<<<<<<< HEAD
// ----------------------------------------
// ✅ 3. التحقق من عدد الحجوزات المكتملة (NEW)
// ----------------------------------------

// أ. حساب عدد الحجوزات الحالية
$stmt_count = $pdo->prepare("SELECT COUNT(id) AS booked_count FROM bookings WHERE event_id = ?");
$stmt_count->execute([$event_id]);
$booked_count = $stmt_count->fetchColumn();

// ب. التحقق من السعة
$capacity = (int)$event['capacity'];
if ($capacity > 0 && $booked_count >= $capacity) {
    $_SESSION['error_message'] = ($lang == 'ar')
        ? '❌ عذراً، الحضور لهذه الفعالية قد اكتمل.'
        : '❌ Sorry, booking for this event is full (capacity reached).';
    header("Location: ?page=event_detail&id=" . $event_id);
    exit;
}

// ----------------------------------------
// 4. التحقق مما إذا كان المستخدم قد حجز مسبقاً
// ----------------------------------------
$stmt = $pdo->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user['id'], $event_id]);
if ($stmt->fetch()) {
    $_SESSION['error_message'] = ($lang == 'ar')
        ? 'لقد قمت بحجز هذه الفعالية مسبقاً.'
        : 'You have already booked this event.';

    // تعديل التوجيه
    header("Location: ?page=student_dashboard");
    exit;
}

// ----------------------------------------
// 5. إنشاء الحجز وإرسال الإيميل
// ----------------------------------------
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
if ($stmt->execute([$user['id'], $event_id])) {
    $booking_id = $pdo->lastInsertId();

=======
// Create booking
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
if ($stmt->execute([$user['id'], $event_id])) {
    $booking_id = $pdo->lastInsertId();
    
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
    // ✉️ Send confirmation email using the new function
    $language = $_SESSION['lang'] ?? 'en';
    $email_sent = sendBookingConfirmation(
        $user['email'],
        $user['name'],
        $event['title'],
        $event['date'],
<<<<<<< HEAD
        $event['end_at'],
        $event['location'],
        $language
    );

     if ($email_sent) {
=======
        $event['location'],
        $language
    );
    
    if ($email_sent) {
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
        $_SESSION['success_message'] = ($_SESSION['lang'] ?? 'en') == 'ar'
            ? '✅ تم تأكيد الحجز! تم إرسال تأكيد إلى بريدك الإلكتروني.'
            : '✅ Booking confirmed! Check your email for confirmation.';
    } else {
        $_SESSION['success_message'] = ($_SESSION['lang'] ?? 'en') == 'ar'
            ? '✅ تم حفظ الحجز (لم يتم إرسال البريد الإلكتروني).'
            : '✅ Booking saved (email not sent).';
    }
} else {
    $_SESSION['error_message'] = ($_SESSION['lang'] ?? 'en') == 'ar'
        ? 'فشل الحجز. حاول مرة أخرى لاحقاً.'
        : 'Booking failed. Try again later.';
    header("Location: ?page=events");
    exit;
}

// 🚀 التعديل النهائي: توجيه المستخدم إلى صفحة الحجوزات المسموح بها في الـ Router
header("Location: ?page=student_dashboard");
exit;
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
