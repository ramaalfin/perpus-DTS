<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Anggota</title>
</head>

<body>
    <h3 style="text-align: center;">Laporan Data Anggota</h3>
    <table width="100%" border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama Anggota</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Email</th>
                <th scope="col">Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;?>
            <?php while($tampil = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <th><?= $i; ?>.</th>
                    <td><?= $tampil['nama']; ?></td>
                    <td><?= $tampil['jenis_kelamin']; ?></td>
                    <td><?= $tampil['email']; ?></td>
                    <td><?= $tampil['alamat']; ?></td>
                </tr>
            <?php $i++ ?>
            <?php endwhile; ?>
        </tbody>
    </table>


</body>

</html>