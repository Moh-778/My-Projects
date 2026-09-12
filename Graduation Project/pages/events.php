<?php
// C:\wamp64\www\Project-2\pages\events.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$lang = $_SESSION['lang'] ?? 'en';
require_once __DIR__ . "/../lang/$lang.php";
$user = current_user();

global $pdo;
$settings = get_system_settings();
$limit = intval($settings['events_per_page'] ?? 9); // العدد الأقصى للفعاليات في الصفحة
$current_page = max(1, intval($_GET['page_num'] ?? 1)); // رقم الصفحة الحالي
$offset = ($current_page - 1) * $limit; // الإزاحة للـ SQL OFFSET

// ✅ Handle search input and cleanup expired events
$deleted_count = cleanup_expired_events();

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// ✅ Fetch categories (used for the filter dropdown)
$categories = $pdo->query("
    SELECT DISTINCT category 
    FROM events 
    WHERE category IS NOT NULL AND category <> '' 
    ORDER BY category ASC
")->fetchAll(PDO::FETCH_COLUMN);

// ✅ Build query dynamically
$base_query = "
    FROM events e
    JOIN users u ON e.organizer_id = u.id 
    WHERE e.approval_status = 'approved'
";
$all_params = []; // مصفوفة واحدة لجميع المعاملات (مسماة فقط)

if ($search) {
    // ✅ استخدام معاملات مسماة لجميع شروط البحث (title, desc, location)
    $base_query .= " AND (e.title LIKE :search_title OR e.description LIKE :search_desc OR e.location LIKE :search_location)";
    $all_params[':search_title'] = "%$search%";
    $all_params[':search_desc'] = "%$search%";
    $all_params[':search_location'] = "%$search%";
}

if ($category) {
    // ✅ استخدام معامل مسمى للتصنيف
    $base_query .= " AND e.category = :category";
    $all_params[':category'] = $category;
}

// 1. جلب العدد الكلي للفعاليات المطابقة لشرط البحث/التصنيف
$stmt_total = $pdo->prepare("SELECT COUNT(e.id) " . $base_query);
// استخدام نفس مصفوفة all_params لجلب العدد الكلي
// ملاحظة: لا حاجة هنا لربط LIMIT/OFFSET
$stmt_total->execute($all_params);
$total_events = $stmt_total->fetchColumn();

// حساب إجمالي عدد الصفحات
$total_pages = ceil($total_events / $limit);

// 2. جلب الفعاليات للصفحة الحالية (مع تطبيق LIMIT و OFFSET)
// استخدام المعاملات المسماة لـ LIMIT و OFFSET
$query_events = "
    SELECT e.*, u.name AS organizer_name
    " . $base_query . " 
    ORDER BY e.date DESC 
    LIMIT :limit OFFSET :offset"; 

// إعداد الاستعلام
$stmt_events = $pdo->prepare($query_events);

// ✅ ربط قيم الترقيم كمعاملات مسماة وتحديد نوع البيانات كعدد صحيح (للتأكد من عدم وضع علامات اقتباس)
$stmt_events->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_events->bindValue(':offset', $offset, PDO::PARAM_INT);

// ✅ ربط جميع معاملات البحث والتصنيف
foreach ($all_params as $key => $value) {
    // يجب ربط معاملات LIKE كـ PDO::PARAM_STR لضمان عمل علامات %
    $stmt_events->bindValue($key, $value, PDO::PARAM_STR); 
}

// ✅ تنفيذ الاستعلام بدون تمرير أي معاملات في execute لأننا ربطناها كلها بالفعل
$stmt_events->execute(); 
$events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="container my-5" dir="<?php echo ($lang == 'ar' ? 'rtl' : 'ltr'); ?>">
    <h2 class="text-center mb-4 text-primary fw-bold"><?php echo lang('available_events'); ?></h2>
     <!-- 🔍 Search & Filter -->
<div class="card shadow-sm border-0 p-4 mb-5" style="border-radius:12px;">
  <form method="GET" action="index.php" class="row g-2">
    <input type="hidden" name="page" value="events">
    <input type="hidden" name="page_num" value="1"> <!-- إعادة الترقيم إلى الصفحة الأولى عند البحث -->
    <div class="col-md-6">
      <input type="text" name="search" class="form-control form-control-lg"
              placeholder="<?php echo lang('search_for_events'); ?>"
              value="<?php echo e($search); ?>">
    </div>
    <div class="col-md-4">
      <select name="category" class="form-select form-select-lg">
        <option value=""><?php echo lang('all_categories'); ?></option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo e($cat); ?>" <?php echo ($cat == $category) ? 'selected' : ''; ?>>
            <?php echo e($cat); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primary btn-lg" style="background:linear-gradient(90deg,#5b46df,#3887f3);border:none;">
        <i class="bi bi-search"></i>
        <?php echo lang('search'); ?>
      </button>
    </div>
  </form>
</div>

   

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <?php 
                    // 2. ✅ التحقق من حالة الحجز لكل فعالية (جديد)
                    $capacity = (int)$event['capacity'];
                    $booking_status = getEventBookingStatus($event['id'], $capacity);
                    $is_full = $booking_status['is_full'];
                    
                    // تحديد لون البطاقة حسب حالة الاكتمال
                    $card_border_class = $is_full ? 'border-danger shadow-lg' : 'border-primary shadow-sm';
                    $badge_html = '';

                    if ($capacity > 0 && $is_full) {
                        $badge_html = '<span class="badge bg-danger position-absolute top-0 start-0 translate-middle mt-2 ms-3 p- rounded-pill">' . lang('Full') . '</span>';
                    }
                ?>
                <div class="col">
                    <div class="card h-100 border-3 <?php echo $card_border_class; ?> position-relative" style="border-radius:12px; overflow:hidden;">
                        <?php echo $badge_html; // عرض شارة الاكتمال ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary mb-3 fw-bold"><?php echo e($event['title']); ?></h5>
                            
                            <p class="card-text text-muted mb-2">
                                <i class="bi bi-calendar-event me-2 text-success"></i> <?php echo date('Y-m-d', strtotime($event['created_at'])); ?>
                            </p>
                            <p class="card-text text-muted mb-2">
                                <i class="bi bi-clock me-2 text-info"></i> <?php echo date('h:i A', strtotime($event['created_at'])); ?>
                            </p>
                            <p class="card-text text-muted mb-3">
                                <i class="bi bi-geo-alt me-2 text-danger"></i> <?php echo e($event['location']); ?>
                            </p>
                            
                            <p class="card-text small mt-auto pt-3">
                                <?php echo lang('organizer'); ?>: <span class="fw-bold text-dark"><?php echo e($event['organizer_name']); ?></span>
                            </p>
                        </div>
                        <div class="card-footer bg-light border-0 pt-0">
                            <!-- زر التفاصيل/الحجز -->
                            <?php if ($is_full): ?>
                                <button class="btn btn-danger w-100 disabled fw-bold">
                                    <i class="bi bi-x-octagon me-2"></i> <?php echo lang('capacity_full'); ?>
                                </button>
                            <?php else: ?>
                                <a href="?page=event_detail&id=<?php echo $event['id']; ?>" class="btn btn-primary w-100 fw-bold" style="background:linear-gradient(90deg,#3887f3,#5b46df);border:none;">
                                    <i class="bi bi-arrow-right me-2"></i> <?php echo lang('view_details_book'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center p-5 rounded-3 border-0 shadow">
                    <i class="bi bi-info-circle display-4 mb-3"></i>
                    <h4><?php echo lang('no_events_found'); ?></h4>
                    <p class="mb-0"><?php echo lang('adjust_filters_or_search_term'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- ✅ الترقيم (Pagination) -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Events Pagination" class="mt-5">
            <ul class="pagination justify-content-center">
                
                <!-- زر السابق -->
                <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" 
                       href="?page=events&page_num=<?php echo $current_page - 1; ?>&search=<?php echo e($search); ?>&category=<?php echo e($category); ?>" 
                       aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <!-- أزرار الصفحات -->
                <?php 
                // منطق عرض الصفحات المجاورة
                $start = max(1, $current_page - 2);
                $end = min($total_pages, $current_page + 2);

                if ($start > 1) echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                        <a class="page-link" 
                           href="?page=events&page_num=<?php echo $i; ?>&search=<?php echo e($search); ?>&category=<?php echo e($category); ?>">
                           <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages) echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>'; ?>
                
                <!-- زر التالي -->
                <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" 
                       href="?page=events&page_num=<?php echo $current_page + 1; ?>&search=<?php echo e($search); ?>&category=<?php echo e($category); ?>" 
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

</section>