<?php
require_once __DIR__ . '/BaseController.php';

class ProdukController extends BaseController
{
    public function index(): void
    {
        require_admin();
        $model = new Produk($this->db);
        $edit = isset($_GET['edit']) ? $model->find((int)$_GET['edit']) : null;
        $q = trim($_GET['q'] ?? '');
        $this->render('produk/index', [
            'edit' => $edit,
            'cats' => (new Kategori($this->db))->allByName(),
            'q'    => $q,
            'rows' => $model->search($q),
        ], 'Data Produk');
    }

    public function save(): void
    {
        require_admin();
        $id = (int)($_POST['id'] ?? 0); $category = (int)($_POST['kategori_id'] ?? 0);
        $code = trim($_POST['kode_produk'] ?? ''); $name = trim($_POST['nama_produk'] ?? '');
        $price = (float)($_POST['harga'] ?? 0); $stock = (int)($_POST['stok'] ?? 0);
        $desc = trim($_POST['deskripsi'] ?? '');

        // Validasi sisi server
        if (!$category || $code === '' || $name === '' || $price < 0 || $stock < 0) {
            flash('Lengkapi data produk. Harga dan stok tidak boleh negatif.', 'danger');
            redirect('index.php?page=products');
        }

        $model = new Produk($this->db);
        try {
            if ($id) $model->update($id, $category, $code, $name, $price, $stock, $desc);
            else $model->create($category, $code, $name, $price, $stock, $desc);
            flash('Data produk berhasil disimpan.');
        } catch (PDOException $ex) {
            flash('Kode produk mungkin sudah digunakan atau data gagal disimpan.', 'danger');
        }
        redirect('index.php?page=products');
    }

    public function delete(): void
    {
        require_admin();
        try {
            (new Produk($this->db))->delete((int)$_POST['id']);
            flash('Produk dihapus.');
        } catch (PDOException $ex) {
            flash('Produk sudah tercatat dalam transaksi sehingga tidak dapat dihapus.', 'danger');
        }
        redirect('index.php?page=products');
    }
}
