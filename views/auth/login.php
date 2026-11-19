    <div class="login-card"><div class="brand login-brand"><span class="brand-icon">P</span><div><b>POS<span>KU</span></b><small>POINT OF SALES</small></div></div>
    <h1>Selamat datang 👋</h1><p class="muted">Masuk untuk mulai mengelola penjualan.</p>
    <form method="post" class="form-stack"><?=csrf_field()?><input type="hidden" name="action" value="login">
      <label>Username</label><input name="username" required autofocus placeholder="Masukkan username">
      <label>Password</label><input type="password" name="password" required placeholder="Masukkan password">
      <button class="btn primary full">Masuk ke Aplikasi</button></form>
    <div class="demo-login"><b>Akun awal aplikasi</b><br>Admin: <code>admin</code> / <code>admin123</code><br>Kasir: <code>kasir</code> / <code>kasir123</code></div></div>
