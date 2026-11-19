    <div class="pos-layout"><div class="panel"><div class="panel-head"><div><h3>Pilih Produk</h3><p>Klik produk untuk memasukkan ke keranjang</p></div></div>
    <input id="productSearch" class="search-input" placeholder="Cari produk...">
    <div class="product-grid" id="productGrid"><?php foreach($products as $p): ?><button type="button" class="product-tile" data-id="<?=$p['id']?>" data-code="<?=e(strtolower($p['kode_produk']))?>" data-name="<?=e(strtolower($p['nama_produk']))?>" data-price="<?=$p['harga']?>" data-stock="<?=$p['stok']?>" data-title="<?=e($p['nama_produk'])?>"><span class="tile-icon">◇</span><b><?=e($p['nama_produk'])?></b><small><?=e($p['kode_produk'])?> · Stok <?=e($p['stok'])?></small><strong><?=rupiah($p['harga'])?></strong></button><?php endforeach; ?></div>
    <?php if(!$products): ?><div class="empty">Belum ada produk dengan stok tersedia. Admin perlu menambahkan produk terlebih dahulu.</div><?php endif; ?></div>
    <div class="panel cart-panel"><div class="panel-head"><div><h3>Keranjang</h3><p>Rincian belanja pelanggan</p></div><button type="button" class="link-danger" id="clearCart">Kosongkan</button></div>
    <div id="cartItems" class="cart-items"><div class="empty">Belum ada produk dipilih.</div></div>
    <form method="post" id="checkoutForm" class="checkout-form"><?=csrf_field()?><input type="hidden" name="action" value="checkout"><input type="hidden" name="cart_json" id="cartJson">
    <label>Pelanggan</label><select name="pelanggan_id"><option value="">Pelanggan umum</option><?php foreach($customers as $c): ?><option value="<?=$c['id']?>"><?=e($c['nama_pelanggan'])?></option><?php endforeach; ?></select>
    <label>Metode Pembayaran</label><select name="metode_pembayaran" id="paymentMethod"><option>Tunai</option><option>QRIS</option><option>Transfer</option><option>Debit</option></select>
    <div class="total-row"><span>Total</span><strong id="grandTotal">Rp 0</strong></div>
    <label>Uang Dibayar (Rp)</label><input type="number" name="bayar" id="paidInput" min="0" value="0" required>
    <div class="total-row change"><span>Kembalian</span><strong id="changeTotal">Rp 0</strong></div>
    <button class="btn primary full" type="submit" id="checkoutButton" disabled>Proses Pembayaran</button></form></div></div>
    <script>
    const cart={};
    const fmt=n=>'Rp '+Number(n).toLocaleString('id-ID',{maximumFractionDigits:0});
    const itemsEl=document.getElementById('cartItems');
    function renderCart(){
      let total=0,html='';
      Object.values(cart).forEach(p=>{total+=p.price*p.qty;html+=`<div class="cart-line"><div><b>${p.title}</b><small>${fmt(p.price)} × ${p.qty}</small></div><div class="qty"><button type="button" data-id="${p.id}" data-delta="-1">−</button><b>${p.qty}</b><button type="button" data-id="${p.id}" data-delta="1">+</button></div><strong>${fmt(p.price*p.qty)}</strong></div>`;});
      itemsEl.innerHTML=html||'<div class="empty">Belum ada produk dipilih.</div>';
      document.getElementById('grandTotal').textContent=fmt(total);
      document.getElementById('cartJson').value=JSON.stringify(Object.values(cart).map(p=>({id:p.id,qty:p.qty})));
      document.getElementById('checkoutButton').disabled=total<=0;
      updateChange();
    }
    document.querySelectorAll('.product-tile').forEach(btn=>btn.addEventListener('click',()=>{
      const id=btn.dataset.id,stock=Number(btn.dataset.stock);
      if(!cart[id]) cart[id]={id,title:btn.dataset.title,price:Number(btn.dataset.price),qty:0,stock};
      if(cart[id].qty<stock) cart[id].qty++;
      else alert('Jumlah melebihi stok tersedia.');
      renderCart();
    }));
    itemsEl.addEventListener('click',ev=>{
      const b=ev.target.closest('button[data-id]');if(!b)return;
      const p=cart[b.dataset.id];p.qty+=Number(b.dataset.delta);
      if(p.qty<=0)delete cart[b.dataset.id];else if(p.qty>p.stock){p.qty=p.stock;alert('Stok tidak mencukupi.');}
      renderCart();
    });
    document.getElementById('clearCart').addEventListener('click',()=>{Object.keys(cart).forEach(k=>delete cart[k]);renderCart();});
    function updateChange(){
      let total=Object.values(cart).reduce((s,p)=>s+p.price*p.qty,0);
      const paid=Number(document.getElementById('paidInput').value||0);
      document.getElementById('changeTotal').textContent=fmt(Math.max(0,paid-total));
      if(document.getElementById('paymentMethod').value!=='Tunai')document.getElementById('paidInput').value=total;
    }
    document.getElementById('paidInput').addEventListener('input',updateChange);
    document.getElementById('paymentMethod').addEventListener('change',updateChange);
    document.getElementById('productSearch').addEventListener('input',ev=>{
      const q=ev.target.value.toLowerCase();
      document.querySelectorAll('.product-tile').forEach(b=>b.hidden=!(b.dataset.name.includes(q)||b.dataset.code.includes(q)));
    });
    document.getElementById('checkoutForm').addEventListener('submit',ev=>{
      if(!Object.keys(cart).length){ev.preventDefault();alert('Keranjang masih kosong.');return;}
      if(document.getElementById('paymentMethod').value==='Tunai'){
        const total=Object.values(cart).reduce((s,p)=>s+p.price*p.qty,0);
        if(Number(document.getElementById('paidInput').value)<total){ev.preventDefault();alert('Uang pembayaran kurang.');}
      }
    });
    renderCart();
    </script>
