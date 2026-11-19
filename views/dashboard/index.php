    <div class="welcome"><div><span class="eyebrow">RINGKASAN TOKO</span><h1>Halo, <?=e($_SESSION['user']['nama'])?>!</h1><p>Berikut ringkasan aktivitas penjualan toko Anda.</p></div><a class="btn primary" href="index.php?page=pos">＋ Transaksi Baru</a></div>
    <div class="stats">
      <div class="stat-card"><div class="stat-icon purple">↗</div><span>Omzet Hari Ini</span><strong><?=rupiah($today['omzet'])?></strong><small>Total penjualan hari ini</small></div>
      <div class="stat-card"><div class="stat-icon blue">▣</div><span>Transaksi Hari Ini</span><strong><?=number_format($today['jumlah'])?></strong><small>Transaksi tersimpan</small></div>
      <div class="stat-card"><div class="stat-icon green">◇</div><span>Total Produk</span><strong><?=number_format($products)?></strong><small>Produk terdaftar</small></div>
      <div class="stat-card"><div class="stat-icon orange">!</div><span>Stok Menipis</span><strong><?=number_format($low)?></strong><small>Stok 5 atau kurang</small></div>
    </div>
    <div class="panel"><div class="panel-head"><div><h3>Transaksi Terbaru</h3><p>Aktivitas penjualan terakhir</p></div><a href="index.php?page=reports" class="text-link">Lihat semua →</a></div>
    <?php if(!$recent): ?><div class="empty">Belum ada transaksi penjualan.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Kode Transaksi</th><th>Tanggal</th><th>Kasir</th><th>Pembayaran</th><th>Total</th></tr></thead><tbody>
    <?php foreach($recent as $r): ?><tr><td><b><?=e($r['kode_transaksi'])?></b></td><td><?=e(date('d/m/Y H:i',strtotime($r['tanggal_transaksi'])))?></td><td><?=e($r['nama_kasir'])?></td><td><span class="pill"><?=e($r['metode_pembayaran'])?></span></td><td><b><?=rupiah($r['total_harga'])?></b></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?></div>
