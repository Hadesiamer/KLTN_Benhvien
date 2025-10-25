<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đổi mật khẩu - Quản lý</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-yzJ7mK8OdnW8k1V9Kw+Gx8jUOyFpu0vVHoAwMFkbT0kaMiEYZQCUvR+Eo8Ep/mRzQhAZZ6C4pB5n0vC7XqQG8g=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/KLTN_Benhvien/public/css/main.css">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

  <!-- ========== HEADER (include ngoài nếu có) ========== -->
  <?php include "blocks/header.php" ?>
  <main class="flex-grow">
    <?php 
      // Gọi trang nội dung chính (form đổi mật khẩu)
      include "./mvc/views/pages/qlydoimk.php"; 
    ?>
  </main>

  <!-- ========== FOOTER (include ngoài nếu có) ========== -->

  <script>
  // ========== JS hỗ trợ cho form ==========
  const $$ = (s) => document.querySelector(s);
  const showToast = () => {
    const t = $$("#toast");
    t.classList.remove("hidden");
    setTimeout(() => t.classList.add("hidden"), 2000);
  };

  const f = $$("#formDoiMatKhau");
  if (f) {
    const oldPw = $$("#old_password");
    const newPw = $$("#new_password");
    const cfmPw = $$("#confirm_password");
    const errOld = $$("#err_old");
    const errNew = $$("#err_new");
    const errCfm = $$("#err_confirm");

    function addErr(el, msg, show) {
      if (show) {
        el.classList.remove("border-gray-300");
        el.classList.add("border-red-500", "focus:ring-red-500");
        msg.classList.remove("hidden");
      } else {
        el.classList.remove("border-red-500", "focus:ring-red-500");
        el.classList.add("border-gray-300");
        msg.classList.add("hidden");
      }
    }

    function validateOld() {
      const ok = oldPw.value.trim().length > 0;
      addErr(oldPw, errOld, !ok);
      return ok;
    }
    function validateNew() {
      const len = newPw.value.length;
      const ok = len >= 8 && len <= 16;
      addErr(newPw, errNew, !ok);
      if (cfmPw.value.length > 0) validateConfirm();
      return ok;
    }
    function validateConfirm() {
      const ok = cfmPw.value === newPw.value && cfmPw.value.length > 0;
      addErr(cfmPw, errCfm, !ok);
      return ok;
    }

    [oldPw, newPw, cfmPw].forEach((el) => {
      el.addEventListener("input", () => { validateOld(); validateNew(); validateConfirm(); });
      el.addEventListener("blur", () => { validateOld(); validateNew(); validateConfirm(); });
    });

    f.addEventListener("submit", (e) => {
      const allOk = validateOld() && validateNew() && validateConfirm();
      if (!allOk) {
        e.preventDefault();
        showToast();
      }
    });

    document.querySelectorAll(".toggle-visibility").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-target");
        const inp = document.getElementById(id);
        if (!inp) return;
        inp.type = inp.type === "password" ? "text" : "password";
        const icon = btn.querySelector("i");
        if (icon) {
          icon.classList.toggle("fa-eye");
          icon.classList.toggle("fa-eye-slash");
        }
        inp.focus();
      });
    });
  }
  </script>

</body>
</html>
