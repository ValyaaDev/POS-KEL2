<?php
class Pelanggan
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function find(int $id)
    {
        $s = $this->db->prepare('SELECT * FROM pelanggan WHERE id=?');
        $s->execute([$id]);
        return $s->fetch();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM pelanggan ORDER BY id DESC')->fetchAll();
    }

    public function allByName(): array
    {
        return $this->db->query('SELECT id,nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan')->fetchAll();
    }

    public function create(array $data): void
    {
        $s = $this->db->prepare('INSERT INTO pelanggan(nama_pelanggan,no_telepon,alamat,email) VALUES(?,?,?,?)');
        $s->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $s = $this->db->prepare('UPDATE pelanggan SET nama_pelanggan=?,no_telepon=?,alamat=?,email=? WHERE id=?');
        $s->execute(array_merge($data, [$id]));
    }

    public function delete(int $id): void
    {
        $s = $this->db->prepare('DELETE FROM pelanggan WHERE id=?');
        $s->execute([$id]);
    }
}
