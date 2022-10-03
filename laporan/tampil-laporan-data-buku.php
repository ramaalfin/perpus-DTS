<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Buku</title>
</head>

<body>
    <h3 style="text-align: center;">Laporan Data Buku</h3>
    <table width="100%" border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Judul Buku</th>
                <th scope="col">Penulis</th>
                <th scope="col">Penerbit</th>
                <th scope="col">Tahun Terbit</th>
                <th scope="col">ISBN</th>
                <th scope="col">Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;?>
            <?php while($tampil = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <th><?= $i; ?>.</th>
                    <td><?= $tampil['judul_buku']; ?></td>
                    <td><?= $tampil['penulis']; ?></td>
                    <td><?= $tampil['penerbit']; ?></td>
                    <td><?= $tampil['tahun_terbit']; ?></td>
                    <td><?= $tampil['ISBN']; ?></td>
                    <td><?= $tampil['stok']; ?></td>
                </tr>
            <?php $i++ ?>
            <?php endwhile; ?>
        </tbody>
    </table>


</body>

</html>