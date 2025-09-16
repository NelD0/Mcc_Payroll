<!-- Reusable Admin Header (Hero) -->
<section class="admin-hero-header py-4 py-md-5 mb-3 mb-md-4">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-12">
        <h1 class="display-6 fw-bold mb-1" style="color:#ffffff; text-shadow:0 2px 8px rgba(0,0,0,.25)">
          <?php echo e($title ?? 'Dashboard'); ?>

        </h1>
        <p class="lead mb-0" style="color:#ecf6ff; opacity:.95">Manage payroll, employees, and timesheets with ease.</p>
      </div>
    </div>
  </div>
</section>

<style>
  /* Admin hero header styling (non-sticky) */
  .admin-hero-header{
    background: linear-gradient(135deg, var(--brand), var(--brand-600));
    border-bottom-left-radius: 18px;
    border-bottom-right-radius: 18px;
    box-shadow: 0 8px 28px rgba(0,0,0,.15);
  }
  .night-mode .admin-hero-header{
    background: linear-gradient(135deg, #1f242b, #2b3138);
  }
</style><?php /**PATH C:\xampp\htdocs\Mcc_Payroll\resources\views/admin/header.blade.php ENDPATH**/ ?>