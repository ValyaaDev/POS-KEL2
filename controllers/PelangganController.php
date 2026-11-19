<?php
require_once __DIR__ . '/BaseController.php';

class PelangganController extends BaseController
{
    public function index(): void
    {
        require_admin();
        $model = new Pelanggan($this->db);
        $edit = isset($_GET['edit']) ? $model->find((int)$_GET['edit']) : null;
        $this->render('pelanggan/index', [
            'edit' => $edit,
            'rows' => $model->all(),
        ], 'Data Pelanggan');
    }

    public function save(): void
    {
        require_admin();
        $id = (int)($_POST['id'] ?? 0); $name = trim($_POST['nama_pelanggan'] ?? '');
        if ($name === '') { flash('Nama pelanggan wajib diisi.', 'danger'); redirect('index.php?page=customers'); }
        $data = [$name, trim($_POST['no_telepon'] ?? ''), trim($_POST['alamat'] ?? ''), trim($_POST['email'] ?? '')];
        $model = new Pelanggan($this->db);
        if ($id) $model->update($id, $data);
        else $model->create($data);
        flash('Data pelanggan berhasil disimpan.');
        redirect('index.php?page=customers');
    }

    public function delete(): void
    {
        require_admin();
        (new Pelanggan($this->db))->delete((int)$_POST['id']);
        flash('Data pelanggan dihapus.');
        redirect('index.php?page=customers');
    }
}
