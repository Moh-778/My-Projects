<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// ✅ Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
<<<<<<< HEAD

=======
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
$settings = get_system_settings();
$registration_is_open = ($site_settings['registration_open'] ?? '1') == '1';

?>

<section class="py-5" style="background: linear-gradient(135deg, #0066ff, #6a11cb); min-height: 90vh; display: flex; align-items: center;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 p-4">
          <div class="card-body">

            <h3 class="text-center fw-bold mb-4 text-primary">
              <?php echo ($_SESSION['lang'] == 'ar') ? 'تسجيل الدخول' : 'Login'; ?>
            </h3>

            <!-- ✅ Success Message After Registration -->
            <?php if (!empty($_SESSION['success_message'])): ?>
              <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
              </div>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- ✅ Error Message (if login fails, optional) -->
            <?php if (!empty($_SESSION['error_message'])): ?>
              <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
              </div>
              <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- ✅ Login Form -->
            <form method="POST" action="?page=login_action">
              <!-- Email -->
              <div class="mb-3">
                <label class="form-label">
                  <?php echo ($_SESSION['lang'] == 'ar') ? 'البريد الإلكتروني' : 'Email Address'; ?>
                </label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label class="form-label">
                  <?php echo ($_SESSION['lang'] == 'ar') ? 'كلمة المرور' : 'Password'; ?>
                </label>
                <input type="password" name="password" class="form-control" required placeholder="********">
              </div>

              <!-- Submit -->
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i>
                <?php echo ($_SESSION['lang'] == 'ar') ? 'تسجيل الدخول' : 'Login'; ?>
              </button>
            </form>

            <!-- Forgot password -->
            <p class="text-center mt-3 mb-1">
              <a href="?page=forgot" class="text-decoration-none">
                <?php echo ($_SESSION['lang'] == 'ar') ? 'نسيت كلمة المرور؟' : 'Forgot Password?'; ?>
              </a>
            </p>

            <!-- Register -->
<<<<<<< HEAD
             <?php if ($registration_is_open): ?>
=======
              <?php if ($registration_is_open): ?>
>>>>>>> 22d48b74c76e845c9871098daa5554d92aaebfc9
              <p class="text-center mt-2 mb-0">
                <?php echo ($_SESSION['lang'] == 'ar') ? 'ليس لديك حساب؟' : 'Don’t have an account?'; ?>
                <a href="?page=register" class="fw-semibold text-primary">
                  <?php echo ($_SESSION['lang'] == 'ar') ? 'إنشاء حساب' : 'Register'; ?>
                </a>
              </p>
              <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>


