<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// ✅ Make sure user is logged in and is an organizer
$user = current_user();
$lang = $_SESSION['lang'] ?? 'en';

if (!$user || $user['role_id'] != 2) {
    $_SESSION['error_message'] = ($lang == 'ar') 
        ? 'غير مصرح لك بتنفيذ هذا الإجراء.' 
        : 'You are not authorized to perform this action.';
    header("Location: " . BASE_URL . "/?page=login");
    exit;
}

<<<<<<< HEAD
// ----------------------------------------
// ✅ 1. Get event ID using $_REQUEST (supports both GET and POST)
// ----------------------------------------
// $_REQUEST سيجلب event_id سواء كان من الرابط (GET) أو من النموذج (POST)
$event_id = $_REQUEST['event_id'] ?? $_REQUEST['id'] ?? null; 

// التحقق من صلاحية المعرف
if (!$event_id || !is_numeric($event_id)) {
    // استخدم رسائل الجلسة بدلاً من die() لتوجيه المستخدم
    $_SESSION['error_message'] = ($lang == 'ar') 
        ? '❌ معرف الحدث غير صالح.' 
        : '❌ Invalid event ID.';
    header("Location: dashboard.php");
    exit;
=======
// ✅ Get event ID
$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    die($_SESSION['lang'] ?? 'en' == 'ar' ? '❌ معرف الحدث غير صالح' :"❌ Invalid event ID");
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
}

// ----------------------------------------
// ✅ 2. Verify the event belongs to this organizer
// ----------------------------------------
try {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM events WHERE id = ? AND organizer_id = ?");
    $stmt->execute([$event_id, $user['id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

<<<<<<< HEAD
    if (!$event) {
        $_SESSION['error_message'] = ($lang == 'ar') 
            ? '❌ ليس لديك الحق في حذف هذا الحدث.' 
            : '❌ You are not authorized to delete this event.';
        header("Location: dashboard.php");
        exit;
    }

    // ----------------------------------------
    // ✅ 3. Delete event
    // ----------------------------------------
    $delete = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $delete->execute([$event_id]);

    // ----------------------------------------
    // ✅ 4. Redirect back to dashboard with success message
    // ----------------------------------------
    $_SESSION['success_message'] = ($lang == 'ar') 
        ? '✅ تم حذف الفعالية بنجاح.' 
        : '✅ Event deleted successfully.';
        
    // يجب التوجيه إلى المسار الصحيح للوحة التحكم
    header("Location: dashboard.php");
    exit;

} catch (PDOException $e) {
    // التعامل مع خطأ قاعدة البيانات
    $_SESSION['error_message'] = ($lang == 'ar') 
        ? 'فشل حذف الفعالية: ' . $e->getMessage() 
        : 'Failed to delete event: ' . $e->getMessage();
    header("Location: dashboard.php");
    exit;
=======
if (!$event) {
    die($_SESSION['lang'] ?? 'en' == 'ar' ? '❌ ليس لديك الحق في حذف هذا الحدث.' :"❌ You are not authorized to delete this event.");
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
}
?>

