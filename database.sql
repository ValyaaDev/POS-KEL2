CREATE DATABASE IF NOT EXISTS pos_native CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pos_native;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pelanggan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_pelanggan VARCHAR(120) NOT NULL,
  no_telepon VARCHAR(30) DEFAULT NULL,
  alamat TEXT DEFAULT NULL,
  email VARCHAR(120) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kategori_id INT NOT NULL,
  kode_produk VARCHAR(40) NOT NULL UNIQUE,
  nama_produk VARCHAR(150) NOT NULL,
  harga DECIMAL(14,2) NOT NULL DEFAULT 0,
  stok INT NOT NULL DEFAULT 0,
  deskripsi TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_produk_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_transaksi VARCHAR(40) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  pelanggan_id INT DEFAULT NULL,
  tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_harga DECIMAL(14,2) NOT NULL,
  metode_pembayaran ENUM('Tunai','QRIS','Transfer','Debit') NOT NULL DEFAULT 'Tunai',
  bayar DECIMAL(14,2) NOT NULL DEFAULT 0,
  kembalian DECIMAL(14,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_transaksi_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detail_transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaksi_id INT NOT NULL,
  produk_id INT NOT NULL,
  nama_produk VARCHAR(150) NOT NULL,
  jumlah INT NOT NULL,
  harga_satuan DECIMAL(14,2) NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_detail_produk FOREIGN KEY (produk_id) REFERENCES produk(id)
) ENGINE=InnoDB;

INSERT IGNORE INTO kategori (nama_kategori) VALUES
('Makanan'),('Minuman'),('Perlengkapan'),('Lainnya');
