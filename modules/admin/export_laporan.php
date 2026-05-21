<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

/** @var mysqli $conn */

header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$data = mysqli_query($conn,"
SELECT
t_penjualan.nomor_nota,
t_penjualan.tgl_transaksi,
m_user.username,
t_penjualan.total_bayar

FROM t_penjualan

INNER JOIN m_user
ON t_penjualan.id_user=m_user.id_user
");
?>

<table border="1">

<tr>
<th>Nota</th>
<th>Tanggal</th>
<th>Kasir</th>
<th>Total</th>
</tr>

<?php while($d=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?= $d['nomor_nota']; ?></td>
<td><?= $d['tgl_transaksi']; ?></td>
<td><?= $d['username']; ?></td>
<td><?= $d['total_bayar']; ?></td>
</tr>

<?php } ?>

</table>
