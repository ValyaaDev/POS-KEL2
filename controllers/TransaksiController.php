<?php
require_once __DIR__ . '/BaseController.php';

class TransaksiController extends BaseController
{
    /** Halaman kasir */
    public function pos(): void
    {
        $this->render('pos/index', [
            'products'  => (new Produk($this->db))->available(),
            'customers' => (new Pelanggan($this->db))->allByName(),
        ], 'Kasir / Transaksi');
    }

    /** Proses pembayaran (POST action=checkout) */
    public function checkout(): void
    {
        $items = json_decode($_POST['cart_json'] ?? '[]', true);
        $customer = ($_POST['pelanggan_id'] ?? '') !== '' ? (int)$_POST['pelanggan_id'] : null;
        $method = $_POST['metode_pembayaran'] ?? 'Tunai';
        $allowed = ['Tunai', 'QRIS', 'Transfer', 'Debit'];
        $paid = (float)($_POST['bayar'] ?? 0);
        if (!is_array($items) || count($items) === 0 || !in_array($method, $allowed, true)) {
            flash('Keranjang kosong atau metode pembayaran tidak valid.', 'danger');
            redirect('index.php?page=pos');
        }
        try {
            [$transactionId, $code] = (new Transaksi($this->db))->checkout((int)$_SESSION['user']['id'], $customer, $method, $paid, $items);
        } catch (Throwable $ex) {
            flash($ex->getMessage(), 'danger');
            redirect('index.php?page=pos');
        }
        flash('Transaksi berhasil disimpan: ' . $code);
        redirect('index.php?page=receipt&id=' . $transactionId);
    }

    /** Struk transaksi */
    public function receipt(): void
    {
        $model = new Transaksi($this->db);
        $id = (int)($_GET['id'] ?? 0);
        $tr = $model->find($id);
        $this->render('struk/show', [
            'tr'      => $tr,
            'details' => $tr ? $model->details($id) : [],
        ], 'Struk Transaksi');
    }
}
