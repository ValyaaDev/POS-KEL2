<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=e($title)?> - POS Native</title><link rel="stylesheet" href="style.css"></head><body>
    <?php if (!empty($_SESSION['user'])): ?><aside class="sidebar">
      <div class="brand"><span class="brand-icon">P</span><div><b>POS<span>KU</span></b><small>POINT OF SALES</small></div></div>
      <div class="nav-label">MENU UTAMA</div>
      <a class="<?=active('dashboard')?>" href="index.php">▦ &nbsp; Dashboard</a>
      <a class="<?=active('pos')?>" href="index.php?page=pos">▣ &nbsp; Kasir / Transaksi</a>
      <div class="nav-label">MASTER DATA</div>
      <?php if (($_SESSION['user']['role']??'')==='admin'): ?>
      <a class="<?=active('products')?>" href="index.php?page=products">◇ &nbsp; Produk</a>
      <a class="<?=active('categories')?>" href="index.php?page=categories">▤ &nbsp; Kategori</a>
      <a class="<?=active('customers')?>" href="index.php?page=customers">♙ &nbsp; Pelanggan</a>
      <?php endif; ?>
      <a class="<?=active('reports')?>" href="index.php?page=reports">▥ &nbsp; Laporan</a>
      <div class="sidebar-bottom"><div class="user-mini"><div class="avatar"><?=e(strtoupper(substr($_SESSION['user']['nama'],0,1)))?></div><div><b><?=e($_SESSION['user']['nama'])?></b><small><?=e(strtoupper($_SESSION['user']['role']))?></small></div></div>
      <form method="post"><?=csrf_field()?><input type="hidden" name="action" value="logout"><button class="logout">↪ Keluar</button></form></div>
    </aside><main class="main"><header class="topbar"><div><small>WORKSPACE / <?=e(strtoupper($title))?></small><h2><?=e($title)?></h2></div><div class="top-user">● &nbsp; <?=e($_SESSION['user']['nama'])?></div></header>
    <?php else: ?><main class="login-shell"><?php endif; ?>
    <section class="content">
    <?php if($flash): ?><div class="alert <?=e($flash['type'])?>"><?=e($flash['message'])?></div><?php endif; ?>
