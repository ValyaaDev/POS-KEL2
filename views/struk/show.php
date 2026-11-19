<?php if (!$tr): ?><div class="alert danger">Transaksi tidak ditemukan.</div><?php else: ?>
      <div class="receipt-wrap"><div class="receipt"><div class="receipt-center"><div class="brand-icon">P</div><h2>POSKU STORE</h2><p>Struk Pembelian</p></div><hr>
      <p>No: <b><?=e($tr['kode_transaksi'])?></b><br>Tanggal: <?=e(date('d/m/Y H:i',strtotime($tr['tanggal_transaksi'])))?><br>Kasir: <?=e($tr['nama_kasir'])?><br>Pelanggan: <?=e($tr['nama_pelanggan']??'Umum')?></p><hr>
      <?php foreach($details as $d): ?><div class="receipt-line"><span><?=e($d['nama_produk'])?><br><?=e($d['jumlah'])?> × <?=rupiah($d['harga_satuan'])?></span><b><?=rupiah($d['subtotal'])?></b></div><?php endforeach; ?>
      <hr><div class="receipt-line"><b>Total</b><b><?=rupiah($tr['total_harga'])?></b></div><div class="receipt-line"><span>Metode</span><b><?=e($tr['metode_pembayaran'])?></b></div><div class="receipt-line"><span>Bayar</span><b><?=rupiah($tr['bayar'])?></b></div><div class="receipt-line"><span>Kembalian</span><b><?=rupiah($tr['kembalian'])?></b></div><hr><div class="receipt-center"><b>Terima kasih telah berbelanja!</b></div></div>
      <div class="receipt-actions"><button class="btn primary" onclick="window.print()">Cetak Struk</button><a class="btn light" href="index.php?page=pos">Transaksi Baru</a></div></div>
<?php endif; ?>
