    <div class="two-col"><div class="panel"><div class="panel-head"><div><h3><?= $edit?'Edit':'Tambah' ?> Pelanggan</h3><p>Data pelanggan toko</p></div></div>
    <form method="post" class="form-stack"><?=csrf_field()?><input type="hidden" name="action" value="save_customer"><input type="hidden" name="id" value="<?=e($edit['id']??'')?>">
    <label>Nama Pelanggan</label><input name="nama_pelanggan" required value="<?=e($edit['nama_pelanggan']??'')?>">
    <label>Nomor Telepon</label><input name="no_telepon" value="<?=e($edit['no_telepon']??'')?>">
    <label>Email</label><input type="email" name="email" value="<?=e($edit['email']??'')?>">
    <label>Alamat</label><textarea name="alamat" rows="3"><?=e($edit['alamat']??'')?></textarea>
    <button class="btn primary">Simpan Pelanggan</button><?php if($edit): ?><a class="btn light" href="index.php?page=customers">Batal</a><?php endif; ?></form></div>
    <div class="panel"><div class="panel-head"><div><h3>Daftar Pelanggan</h3><p><?=count($rows)?> pelanggan</p></div></div><div class="table-wrap"><table><thead><tr><th>Nama</th><th>Telepon</th><th>Email</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach($rows as $r): ?><tr><td><b><?=e($r['nama_pelanggan'])?></b></td><td><?=e($r['no_telepon'])?></td><td><?=e($r['email'])?></td><td class="actions"><a class="text-link" href="index.php?page=customers&edit=<?=$r['id']?>">Edit</a><form method="post" onsubmit="return confirm('Hapus pelanggan ini?')"><?=csrf_field()?><input type="hidden" name="action" value="delete_customer"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach; ?>
    </tbody></table></div></div></div>
