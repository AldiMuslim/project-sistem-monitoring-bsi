<h2>Tambah Transaksi</h2>

<form method="POST" action="/transaksi">
@csrf

Nama Barang <br>
<input type="text" name="barang"><br><br>

Jumlah <br>
<input type="number" name="jumlah"><br><br>

<button type="submit">Simpan</button>

</form>