<?php
class Produk
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function find(int $id)
    {
        $s = $this->db->prepare('SELECT * FROM produk WHERE id=?');
        $s->execute([$id]);
        return $s->fetch();
    }

    public function search(string $q): array
    {
        $s = $this->db->prepare('SELECT p.*,k.nama_kategori FROM produk p JOIN kategori k ON k.id=p.kategori_id WHERE p.nama_produk LIKE ? OR p.kode_produk LIKE ? ORDER BY p.id DESC');
        $s->execute(["%$q%", "%$q%"]);
        return $s->fetchAll();
    }

    public function available(): array
    {
        return $this->db->query('SELECT id,kode_produk,nama_produk,harga,stok FROM produk WHERE stok>0 ORDER BY nama_produk')->fetchAll();
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM produk')->fetchColumn();
    }

    public function countLowStock(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM produk WHERE stok<=5')->fetchColumn();
    }

    public function create(int $kategoriId, string $kode, string $nama, float $harga, int $stok, string $deskripsi): void
    {
        $s = $this->db->prepare('INSERT INTO produk(kategori_id,kode_produk,nama_produk,harga,stok,deskripsi) VALUES(?,?,?,?,?,?)');
        $s->execute([$kategoriId, $kode, $nama, $harga, $stok, $deskripsi]);
    }

    public function update(int $id, int $kategoriId, string $kode, string $nama, float $harga, int $stok, string $deskripsi): void
    {
        $s = $this->db->prepare('UPDATE produk SET kategori_id=?,kode_produk=?,nama_produk=?,harga=?,stok=?,deskripsi=? WHERE id=?');
        $s->execute([$kategoriId, $kode, $nama, $harga, $stok, $deskripsi, $id]);
    }

    public function delete(int $id): void
    {
        $s = $this->db->prepare('DELETE FROM produk WHERE id=?');
        $s->execute([$id]);
    }
}
