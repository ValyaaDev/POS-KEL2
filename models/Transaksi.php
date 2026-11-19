<?php
class Transaksi
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function todaySummary(): array
    {
        return $this->db->query("SELECT COUNT(*) jumlah, COALESCE(SUM(total_harga),0) omzet FROM transaksi WHERE DATE(tanggal_transaksi)=CURDATE()")->fetch();
    }

    public function recent(int $limit = 6): array
    {
        return $this->db->query('SELECT t.*,u.nama nama_kasir FROM transaksi t JOIN users u ON u.id=t.user_id ORDER BY t.id DESC LIMIT ' . (int)$limit)->fetchAll();
    }

    public function report(string $from, string $to): array
    {
        $s = $this->db->prepare('SELECT t.*,u.nama nama_kasir,p.nama_pelanggan FROM transaksi t JOIN users u ON u.id=t.user_id LEFT JOIN pelanggan p ON p.id=t.pelanggan_id WHERE DATE(t.tanggal_transaksi) BETWEEN ? AND ? ORDER BY t.id DESC');
        $s->execute([$from, $to]);
        return $s->fetchAll();
    }

    public function find(int $id)
    {
        $s = $this->db->prepare('SELECT t.*,u.nama nama_kasir,p.nama_pelanggan FROM transaksi t JOIN users u ON u.id=t.user_id LEFT JOIN pelanggan p ON p.id=t.pelanggan_id WHERE t.id=?');
        $s->execute([$id]);
        return $s->fetch();
    }

    public function details(int $transaksiId): array
    {
        $s = $this->db->prepare('SELECT * FROM detail_transaksi WHERE transaksi_id=?');
        $s->execute([$transaksiId]);
        return $s->fetchAll();
    }

    /**
     * Menyimpan transaksi dalam satu database transaction dan mengurangi stok.
     * Melempar Exception bila data tidak valid; transaksi otomatis di-rollback.
     * @return array [id transaksi, kode transaksi]
     */
    public function checkout(int $userId, ?int $customer, string $method, float $paid, array $items): array
    {
        try {
            $this->db->beginTransaction();
            $total = 0; $clean = [];
            foreach ($items as $item) {
                $pid = (int)($item['id'] ?? 0); $qty = (int)($item['qty'] ?? 0);
                if ($pid < 1 || $qty < 1) throw new Exception('Item transaksi tidak valid.');
                $s = $this->db->prepare('SELECT id,nama_produk,harga,stok FROM produk WHERE id=? FOR UPDATE');
                $s->execute([$pid]); $product = $s->fetch();
                if (!$product) throw new Exception('Produk tidak ditemukan.');
                if ($product['stok'] < $qty) throw new Exception('Stok ' . $product['nama_produk'] . ' tidak mencukupi.');
                $subtotal = (float)$product['harga'] * $qty; $total += $subtotal;
                $clean[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
            }
            if ($method === 'Tunai' && $paid < $total) throw new Exception('Uang pembayaran kurang dari total belanja.');
            if ($method !== 'Tunai') $paid = $total;
            $change = max(0, $paid - $total);
            $code = 'TRX' . date('YmdHis') . random_int(10, 99);
            $s = $this->db->prepare('INSERT INTO transaksi(kode_transaksi,user_id,pelanggan_id,total_harga,metode_pembayaran,bayar,kembalian) VALUES(?,?,?,?,?,?,?)');
            $s->execute([$code, $userId, $customer, $total, $method, $paid, $change]);
            $transactionId = (int)$this->db->lastInsertId();
            $detail = $this->db->prepare('INSERT INTO detail_transaksi(transaksi_id,produk_id,nama_produk,jumlah,harga_satuan,subtotal) VALUES(?,?,?,?,?,?)');
            $stock = $this->db->prepare('UPDATE produk SET stok=stok-? WHERE id=?');
            foreach ($clean as $line) {
                $p = $line['product']; $q = $line['qty'];
                $detail->execute([$transactionId, $p['id'], $p['nama_produk'], $q, $p['harga'], $line['subtotal']]);
                $stock->execute([$q, $p['id']]);
            }
            $this->db->commit();
            return [$transactionId, $code];
        } catch (Throwable $ex) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $ex;
        }
    }
}
