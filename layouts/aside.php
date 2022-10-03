<aside class="sidebar" id="navigation-demo">
    <div class="navigation-button">
        <button class="button-nav">
           <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="">
        </button>
    </div>
    <div class="container-logo mt-lg-5">
        <img src="../assets/images/logo.png" alt="Logo" width="52" height="50" />
        <div>
            <h6 class="judul-logo">Blu Books</h6>
            <p class="tag-logo">Kumpulan Buku Keren</p>
        </div>
    </div>

    <hr>
    <?php
    if (isset($href)) {

    ?>
        <nav class="menu flex-fill">
            <div class="section-menu">
                <a href="<?= $href[0] ?>" class="item-menu <?= ($module == "beranda") ? 'active' : '' ?>">
                    <img src="../assets/images/icon/house-door.svg" width="24" height="24" alt="">
                    <p>Beranda</p>
                </a>
            </div>
            <div class="section-menu mt-3">
                <p class="mb-2 py-2 sub-menu">Entry Data Dan Transaksi</p>

                <a href="<?= $href[1] ?>" class="item-menu <?= ($module == "dataAnggota") ? 'active' : '' ?>">
                <img src="../assets/images/icon/person.svg" width="24" height="24" alt="">

                    <p>Data Anggota</p>
                </a>
                <a href="<?= $href[2] ?>" class="item-menu <?= ($module == "dataBuku") ? 'active' : '' ?>">
                <img src="../assets/images/icon/book.svg" width="24" height="24" alt="">

                    <p>Data Buku</p>
                </a>
                <a href="<?= $href[3] ?>" class="item-menu <?= ($module == "dataPeminjaman") ? 'active' : '' ?>">
                    <img src="../assets/images/icon/box-arrow-up-right.svg" width="20" height="20" alt="">
                    <p>Transaksi Peminjaman</p>
                </a>
                <a href="<?= $href[4] ?>" class="item-menu <?= ($module == "dataPengembalian") ? 'active' : '' ?>">
                    <img src="../assets/images/icon/box-arrow-in-down-left.svg" width="20" hei="20" alt="">
                    <p>Transaksi Pengembalian</p>
                </a>

            </div>

            <div class="section-menu mt-3">
                <p class="mb-2 py-2 sub-menu">Laporan</p>

                <a href="<?= $href[5] ?>" class="item-menu  <?= ($module == "lapDataAnggota") ? 'active' : '' ?>"">
                <img src="../assets/images/icon/person-lines-fill.svg" width="24" height="24" alt="">

                    <p>Laporan Data Anggota</p>
                </a>
                <a href="<?= $href[6] ?>" class="item-menu  <?= ($module == "lapDataBuku") ? 'active' : '' ?>"">
                <img src="../assets/images/icon/journal-text.svg" width="24" height="24" alt="">

                    <p>Laporan Data Buku</p>
                </a>
            </div>

            <div class="section-menu mt-3">
                <a href="../logout.php" class="item-menu" >
                <img src="../assets/images/icon/escape.svg" width="20" height="20" alt="">
                    Logout
                </a>
            </div>
        </nav>
    <?php
    }
    ?>
</aside>