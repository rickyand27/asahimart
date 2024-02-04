<?php
require 'function.php';
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

  <div id="layoutSidenav">

    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h1> <i class="fa fa-shopping-cart"></i> A S A H I <sup>Mart</sup></h1>
          <h1 class="mt-4">Order dengan ID : <?= $ido; ?></h1>
          <h4 class="mt-4">Nama Customer : <?= $np; ?></h4>
          <div class="card mb-4">
            <div class="card-header">
              Rincian Transaksi
            </div>
            <div class="card-body">
              <table id="" class="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
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
                    $subtotal = $harga * $qty;
                    $grandtotal += $subtotal;
                    $iddp = $dt['iddetail'];
                  ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $np; ?> - <?= $desc; ?></td>
                      <td>Rp. <?= number_format($harga); ?></td>
                      <td><?= number_format($qty); ?></td>
                      <td>Rp. <?= number_format($subtotal); ?></td>

                    </tr>



                  <?php
                  }; //end of while
                  ?>
                  <tr>
                    <td colspan="5"> Grand Total : Rp. <?= number_format($grandtotal); ?></td>
                  </tr>
                  <tr>
                    <td colspan="5"> Terbilang : <?= ucfirst(terbilang($grandtotal)); ?> rupiah</td>
                  </tr>


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

  <script>
    window.print()
  </script>

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