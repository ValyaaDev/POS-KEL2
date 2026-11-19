<?php
require_once __DIR__ . '/BaseController.php';

class KategoriController extends BaseController
{
    public function index(): void
    {
        require_admin();
        $model = new Kategori($this->db);
        $edit = isset($_GET['edit']) ? $model->find((int)$_GET['edit']) : null;
        $this->render('kategori/index', [
            'edit' => $edit,
            'rows' => $model->allWithProductCount(),
        ], 'Data Kategori');
    }

    public function save(): void
    {
        require_admin();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['nama_kategori'] ?? '');
        if ($name === '') { flash('Nama kategori wajib diisi.', 'danger'); redirect('index.php?page=categories'); }
        $model = new Kategori($this->db);
        try {
            if ($id) $model->update($id, $name);
            else $model->create($name);
            flash('Data kategori berhasil disimpan.');
        } catch (PDOException $ex) {
            flash('Kategori sudah ada atau gagal disimpan.', 'danger');
        }
        redirect('index.php?page=categories');
    }

    public function delete(): void
    {
        require_admin();
        try {
            (new Kategori($this->db))->delete((int)$_POST['id']);
            flash('Kategori dihapus.');
        } catch (PDOException $ex) {
            flash('Kategori tidak dapat dihapus karena masih digunakan produk.', 'danger');
        }
        redirect('index.php?page=categories');
    }
}
