<?php
require_once __DIR__ . '/BaseController.php';

class LaporanController extends BaseController
{
    public function index(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');
        $rows = (new Transaksi($this->db))->report($from, $to);
        $this->render('laporan/index', [
            'from' => $from,
            'to'   => $to,
            'rows' => $rows,
            'sum'  => array_sum(array_column($rows, 'total_harga')),
        ], 'Laporan Penjualan');
    }
}
