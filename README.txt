APLIKASI POSKU - PHP NATIVE + MYSQL

FITUR
- Login Admin dan Kasir berbasis session.
- Dashboard ringkasan omzet, transaksi, jumlah produk, dan stok menipis.
- CRUD kategori, produk, dan pelanggan (khusus admin).
- Kasir: keranjang, hitung total, metode pembayaran, uang bayar, kembalian.
- Checkout menyimpan transaksi dan detail transaksi serta mengurangi stok.
- Laporan berdasarkan rentang tanggal dan cetak struk.
- Prepared statement, password hashing, CSRF token, dan database transaction.

CARA MENJALANKAN DI XAMPP
1. Ekstrak folder POS_Native_PHP ke C:\\xampp\\htdocs\\
2. Buka XAMPP Control Panel, jalankan Apache dan MySQL.
3. Buka http://localhost/phpmyadmin
4. Pilih menu Import, pilih file database.sql dari folder POS_Native_PHP, lalu klik Import.
   File SQL akan membuat database bernama pos_native.
5. Pastikan pengaturan database di config.php sesuai:
   host=localhost, dbname=pos_native, dbuser=root, dbpass=''
6. Buka http://localhost/POS_Native_PHP/

AKUN AWAL
Admin : admin / admin123
Kasir : kasir / kasir123

Catatan:
- Akun awal dibuat otomatis ketika tabel users masih kosong.
- Ganti password akun awal sebelum aplikasi dipakai sungguhan.
- Contoh ini ditujukan untuk pembelajaran/lokal, belum untuk deployment publik.
- Untuk keamanan produksi, tambahkan pengelolaan user, pembatasan akses yang lebih rinci,
  backup database, validasi tambahan, dan konfigurasi HTTPS.
