document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = document.querySelector('input[type="email"]').value.trim();
  const password = document.querySelector('input[type="password"]').value.trim();

  if (!email || !password) {
    alert("Email dan password wajib diisi.");
    return;
  }

  fetch("http://localhost:8000/api/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
    body: JSON.stringify({ email, password })
  })
    .then(async response => {
      const data = await response.json();

      if (!response.ok) {
        const msg = data?.message || "Gagal login.";
        throw new Error(msg);
      }

      // Simpan token dan info user
      localStorage.setItem("auth_token", data.token);
      localStorage.setItem("user_name", data.user.name);
      localStorage.setItem("user_role", data.user.role);

      alert("Login berhasil! Selamat datang, " + data.user.name);

      // Redirect ke dashboard
      window.location.href = "menu.php";
    })
    .catch(error => {
      console.error("Login error:", error);
      alert(error.message || "Terjadi kesalahan login.");
    });
});
