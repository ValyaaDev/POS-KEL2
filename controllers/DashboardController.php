<?php
require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController
{
    public function index(): void
    {
        $trx = new Transaksi($this->db);
        $produk = new Produk($this->db);
        $this->render('dashboard/index', [
            'today'    => $trx->todaySummary(),
            'products' => $produk->countAll(),
            'low'      => $produk->countLowStock(),
            'recent'   => $trx->recent(6),
        ], 'Dashboard');
    }
}
