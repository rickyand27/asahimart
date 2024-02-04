<?php
require 'ceklogin.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Incoming Goods</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<body class="sb-nav-fixed">
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php"> <i class="fa fa-shopping-cart"></i> A S A H I <sup>Mart</sup></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

  </nav>
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <a class="nav-link" href="index.php">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Menu</div>

            <a class="nav-link" href="order.php">
              <div class="sb-nav-link-icon"><i class="fas fa-scroll"></i></i></div>
              Order
            </a>
            <a class="nav-link" href="product.php">
              <div class="sb-nav-link-icon"><i class="fas fa-dice-d6"></i></div>
              Product
            </a>
            <a class="nav-link" href="customer.php">
              <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
              Customer
            </a>
            <a class="nav-link" href="incominggoods.php">
              <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
              Incoming Goods
            </a>
            <a class="nav-link" href="logout.php">
              <div class="sb-nav-link-icon"><i class="fas fa-sign-out"></i></div>
              Logout
            </a>
          </div>
        </div>
      </nav>
    </div>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h1 class="mt-4"><i class=" fas fa-truck"></i> Incoming Goods</h1>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Menu untuk menambahkan barang masuk</li>
          </ol>
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addInc">
            <i class="fas fa-plus"></i> Tambah Barang Masuk
          </button>
          <a href="historyig.php" class="btn btn-primary mb-4"><i class="fa fa-clock"></i> Riwayat</a>


          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Data
            </div>
            <div class="card-body">
              <table id="datatablesSimple">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Dibuat</th>
                    <th>Terakhir Diubah</th>
                    <th>Aksi</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $get = mysqli_query($conn, "SELECT * FROM incominggoods i, product p WHERE i.idproduct = p.idproduct AND i.deleted_at IS NULL");
                  $i = 1;
                  while ($p = mysqli_fetch_array($get)) {
                    $np = $p['productname'];
                    $desc = $p['description'];
                    $qty = $p['qty'];
                    $idinc = $p['idinc'];
                    $idp = $p['idproduct'];
                    $create = $p['created_at'];
                    $update = $p['updated_at'];

                  ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $np; ?> - <?= $desc; ?></td>
                      <td><?= number_format($qty); ?></td>
                      <td><?= $create; ?></td>
                      <td><?= $update; ?></td>
                      <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit<?= $idinc; ?>">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idinc; ?>">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </td>
                    </tr>

                    <!-- Modal untuk edit -->
                    <div class="modal fade" id="edit<?= $idinc; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Ubah Barang Masuk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post">
                            <div class="modal-body">
                              <input type="text" name="namaproduk" class="form-control" placeholder="Nama Produk" value="<?= $np; ?> - <?= $desc; ?>" disabled>
                              <input type="number" name="qty" class="form-control  mt-2" placeholder="Qty" required min="1" value="<?= $qty; ?>">
                              <input type="hidden" name="idinc" value="<?= $idinc; ?>">
                              <input type="hidden" name="idp" value="<?= $idp; ?>">
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" name="ubahbarangmasuk">Submit</button>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Modal untuk hapus-->
                    <div class="modal fade" id="delete<?= $idinc; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Pesan Konfirmasi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post">

                            <div class="modal-body">
                              Apakah Anda yakin ingin menghapus barang masuk ini ?
                              <input type="hidden" name="idinc" value="<?= $idinc; ?>">
                              <input type="hidden" name="idp" value="<?= $idp; ?>">
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" name="hapusbarangmasuk">Ya</button>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>

                  <?php
                  }; //end of while
                  ?>


                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Asahimart 2024</div>

          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
  <script src="js/datatables-simple-demo.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

<!-- Modal -->
<div class="modal fade" id="addInc" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tambah Detail Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="post">

        <div class="modal-body">
          Pilih Barang
          <select name="idproduct" class="form-control">

            <?php
            $get = mysqli_query($conn, "SELECT * FROM product");

            while ($p = mysqli_fetch_array($get)) {
              $idp = $p['idproduct'];
              $np = $p['productname'];
              $desc = $p['description'];
              $stock = $p['stock'];

            ?>
              <option value="<?= $idp; ?>"><?= $np; ?> - <?= $desc; ?> (Stock: <?= $stock; ?>)</option>
            <?php } ?>
          </select>

          <input type="number" name="qty" class="form-control mt-2" placeholder="Qty" required min="1">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" name="tambahbarangmasuk">Submit</button>
        </div>

      </form>
    </div>
  </div>
</div>

</html>