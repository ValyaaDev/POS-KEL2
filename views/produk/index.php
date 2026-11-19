    <div class="panel"><div class="panel-head"><div><h3><?= $edit?'Edit Produk':'Tambah Produk' ?></h3><p>Isi informasi produk dan persediaan</p></div></div>
    <form method="post" class="form-grid"><?=csrf_field()?><input type="hidden" name="action" value="save_product"><input type="hidden" name="id" value="<?=e($edit['id']??'')?>">
      <div><label>Kode Produk</label><input name="kode_produk" required value="<?=e($edit['kode_produk']??'')?>"></div>
      <div><label>Nama Produk</label><input name="nama_produk" required value="<?=e($edit['nama_produk']??'')?>"></div>
      <div><label>Kategori</label><select name="kategori_id" required><option value="">Pilih kategori</option><?php foreach($cats as $c): ?><option value="<?=$c['id']?>" <?=((int)($edit['kategori_id']??0)===(int)$c['id'])?'selected':''?>><?=e($c['nama_kategori'])?></option><?php endforeach; ?></select></div>
      <div><label>Harga (Rp)</label><input type="number" name="harga" min="0" step="100" required value="<?=e($edit['harga']??'')?>"></div>
      <div><label>Stok</label><input type="number" name="stok" min="0" step="1" required value="<?=e($edit['stok']??'0')?>"></div>
      <div><label>Deskripsi</label><input name="deskripsi" value="<?=e($edit['deskripsi']??'')?>"></div>
      <div class="form-actions"><button class="btn primary"><?= $edit?'Simpan Perubahan':'Simpan Produk' ?></button><?php if($edit): ?><a class="btn light" href="index.php?page=products">Batal</a><?php endif; ?></div>
    </form></div>
    <div class="panel"><div class="panel-head"><div><h3>Daftar Produk</h3><p><?=count($rows)?> produk ditemukan</p></div><form class="search" method="get"><input type="hidden" name="page" value="products"><input name="q" value="<?=e($q)?>" placeholder="Cari nama / kode..."><button class="btn light">Cari</button></form></div>
    <div class="table-wrap"><table><thead><tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach($rows as $r): ?><tr><td><?=e($r['kode_produk'])?></td><td><b><?=e($r['nama_produk'])?></b></td><td><?=e($r['nama_kategori'])?></td><td><?=rupiah($r['harga'])?></td><td><span class="stock <?=((int)$r['stok']<=5)?'low':''?>"><?=e($r['stok'])?></span></td><td class="actions"><a class="text-link" href="index.php?page=products&edit=<?=$r['id']?>">Edit</a><form method="post" onsubmit="return confirm('Hapus produk ini?')"><?=csrf_field()?><input type="hidden" name="action" value="delete_product"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
