<?php
require 'ceklogin.php';
if (isset($_GET['ido'])) {
  $ido = $_GET['ido'];

  $getcust = mysqli_query($conn, "SELECT * FROM orders o, customer c WHERE o.idcustomer = c.idcustomer and o.idorder='$ido'");
  $getcust2 = mysqli_fetch_array($getcust);
  $np = $getcust2['customername'];
} else {
  header('location:order.php');
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Detail Order</title>
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
              <div class="sb-nav-link-icon"><i class="	fas fa-truck"></i></div>
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
          <h1 class="mt-4">Order dengan ID : <?= $ido; ?></h1>
          <h4 class="mt-4">Nama Customer : <?= $np; ?></h4>

          <a href="historyorder.php" class="btn btn-primary mb-4"><i class="fa fa-arrow-left"></i> Back</a>

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
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                    <th>Dibuat</th>
                    <th>Terakhir Diubah</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $get = mysqli_query($conn, "SELECT * FROM detailorder dt, product p WHERE dt.idproduct = p.idproduct and dt.idorder='$ido'");
                  $i = 1;
                  $grandtotal = 0;
                  while ($dt = mysqli_fetch_array($get)) {

                    $iddp = $dt['iddetail'];
                    $idp = $dt['idproduct'];
                    $np = $dt['productname'];
                    $desc = $dt['description'];
                    $harga = $dt['price'];
                    $qty = $dt['qty'];
                    $stock = $dt['stock'];
                    $subtotal = $harga * $qty;
                    $grandtotal += $subtotal;
                    $iddp = $dt['iddetail'];
                    $create = $dt['created_at'];
                    $update = $dt['updated_at'];
                  ?>
                    <tr>
                      <td class=""><?= $i++; ?></td>
                      <td><?= $np; ?> - <?= $desc; ?></td>
                      <td>
                        <p class="text-right">Rp. <?= number_format($harga); ?></p>
                      </td>
                      <td>
                        <p class="text-right"><?= number_format($qty); ?></p>
                      </td>
                      <td>
                        <p class="text-right">Rp. <?= number_format($subtotal); ?></p>
                      </td>
                      <td><?= $create; ?></td>
                      <td><?= $update; ?></td>
                    </tr>



                  <?php
                  }; //end of while
                  ?>


                </tbody>
              </table>
              <h6> Grand Total : Rp. <?= number_format($grandtotal); ?> </h6>
              <h6>Terbilang : <?= ucfirst(terbilang($grandtotal)); ?> rupiah</h6>

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


</html>