<?php
class Kategori
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function find(int $id)
    {
        $s = $this->db->prepare('SELECT * FROM kategori WHERE id=?');
        $s->execute([$id]);
        return $s->fetch();
    }

    public function allWithProductCount(): array
    {
        return $this->db->query('SELECT k.*,COUNT(p.id) jumlah_produk FROM kategori k LEFT JOIN produk p ON p.kategori_id=k.id GROUP BY k.id ORDER BY k.id DESC')->fetchAll();
    }

    public function allByName(): array
    {
        return $this->db->query('SELECT * FROM kategori ORDER BY nama_kategori')->fetchAll();
    }

    public function create(string $name): void
    {
        $s = $this->db->prepare('INSERT INTO kategori(nama_kategori) VALUES(?)');
        $s->execute([$name]);
    }

    public function update(int $id, string $name): void
    {
        $s = $this->db->prepare('UPDATE kategori SET nama_kategori=? WHERE id=?');
        $s->execute([$name, $id]);
    }

    public function delete(int $id): void
    {
        $s = $this->db->prepare('DELETE FROM kategori WHERE id=?');
        $s->execute([$id]);
    }
}
