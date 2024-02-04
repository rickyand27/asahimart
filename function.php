<?php
session_start();
// koneksi
$conn = mysqli_connect('localhost', 'root', '', 'asahimart_db');


// login
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");
  $check2 = mysqli_num_rows($check);

  if ($check2 > 0) {
    // if (!isset($_SESSION)) session_start();
    $_SESSION['login'] = true;
    header('location:index.php');
  } else {
    echo '
    <script> alert("Username atau Password salah");
    window.location.href="login.php"
    </script>
    ';
  }
}



// ORDER

//tambah order
if (isset($_POST['tambahorder'])) {
  $idcust = $_POST['idcustomer'];

  $query = mysqli_query($conn, "INSERT INTO orders (idcustomer) VALUES ('$idcust')");

  if ($query) {
    header('location:order.php');
  } else {
    echo '
    <script> alert("Gagal menambahkan order baru");
    window.location.href="order.php"
    </script>
    ';
  }
}


//hapus order
if (isset($_POST['hapusorder'])) {
  $ido = $_POST['idorder'];

  $check = mysqli_query($conn, "SELECT * FROM detailorder WHERE idorder='$ido' ");

  while ($ok = mysqli_fetch_array($check)) {
    //balikin stok
    $qty = $ok['qty'];
    $idp = $ok['idproduct'];
    $iddp = $ok['iddetail'];


    //hitung stok dlu
    $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocksekarang = $hitung2['stock'];

    $newStock = $qty + $stocksekarang;

    $query = mysqli_query($conn, "UPDATE product SET stock='$newStock' WHERE idproduct = '$idp'");

    //hapus data
    // $query2 = mysqli_query($conn, "DELETE FROM detailorder WHERE iddetail='$iddp'");
  }

  $query3 = mysqli_query($conn, "UPDATE orders SET deleted_at = NOW() WHERE idorder='$ido'");

  if ($query && $query3) {
    header('location: order.php');
  } else {
    echo '
    <script> alert("Gagal menghapus order");
    window.location.href="order.php"
    </script>
    ';
  }
}

//restore order
if (isset($_POST['restoreorder'])) {
  $ido = $_POST['idorder'];

  $check = mysqli_query($conn, "SELECT * FROM detailorder WHERE idorder='$ido' ");

  while ($ok = mysqli_fetch_array($check)) {
    //balikin stok
    $qty = $ok['qty'];
    $idp = $ok['idproduct'];
    $iddp = $ok['iddetail'];


    //hitung stok dlu
    $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocksekarang = $hitung2['stock'];

    $newStock = $stocksekarang - $qty;
    if ($newStock >= 0) {
      $query = mysqli_query($conn, "UPDATE product SET stock='$newStock' WHERE idproduct = '$idp'");
    } else {
      echo '
      <script> alert("Gagal mengembalikan order. Stok produk tidak bisa kurang dari 0.");
      window.location.href="historyorder.php"
      </script>
      ';
      exit;
    }
  }

  $query3 = mysqli_query($conn, "UPDATE orders SET deleted_at = NULL WHERE idorder='$ido'");

  if ($query && $query3) {
    header('location: order.php');
  } else {
    echo '
    <script> alert("Gagal mengembalikan order");
    window.location.href="historyorder.php"
    </script>
    ';
  }
}


// DETAIL ORDER

//tambah detail
if (isset($_POST['tambahdetail'])) {
  $ido = $_POST['ido'];
  $idp = $_POST['idproduct'];
  $qty = $_POST['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  if ($stocksekarang > $qty) {
    // cukup
    $selisih = $stocksekarang - $qty;
    //insert
    $query = mysqli_query($conn, "INSERT INTO detailorder (idorder, idproduct, qty) VALUES ('$ido','$idp','$qty')");

    //update
    $query2 = mysqli_query($conn, "UPDATE product SET stock='$selisih' WHERE idproduct = '$idp'");

    if ($query && $query2) {
      header('location:view.php?ido=' . $ido);
    } else {
      echo '
      <script> alert("Gagal menambahkan detail baru");
      window.location.href="view.php?ido=' . $ido . '"
      </script>
      ';
    }
  } else {
    //ga cukup
    echo '
    <script> alert("Gagal menambahkan detail baru karena stock tidak cukup");
    window.location.href="view.php?ido=' . $ido . '"
    </script>
    ';
  }
}


//ubah detail barang 
if (isset($_POST['ubahdetailbarang'])) {
  $ido = $_POST['idorder'];
  $idp = $_POST['idproduk'];
  $iddp = $_POST['iddetail'];
  $qty = $_POST['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //ambil qty barang masuk sebelumnya
  $hitung1 = mysqli_query($conn, "SELECT * FROM detailorder WHERE iddetail = '$iddp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $qtysebelum = $hitung2['qty'];

  //stock setelah diubah
  $stockFinal = $stocksekarang + $qtysebelum - $qty;

  if ($stockFinal >= 0) {
    $query = mysqli_query($conn, "UPDATE detailorder SET qty='$qty' WHERE iddetail = '$iddp'");

    $query2 = mysqli_query($conn, "UPDATE product SET stock='$stockFinal' WHERE idproduct = '$idp'");

    if ($query && $query2) {
      header('location:view.php?ido=' . $ido);
    } else {
      echo '
      <script> alert("Gagal menghapus detail barang");
      window.location.href="view.php?ido=' . $ido . '"
      </script>
      ';
    }
  } else {
    echo '
    <script> alert("Qty terlalu besar, melebihi stok saat ini");
    window.location.href="view.php?ido=' . $ido . '"
    </script>
    ';
  }
}



//hapus detail order
if (isset($_POST['hapusdetail'])) {
  $iddp = $_POST['iddp'];
  $idp = $_POST['idp'];
  $ido = $_POST['ido'];

  $check = mysqli_query($conn, "SELECT * FROM detailorder WHERE iddetail='$iddp'");
  $check2 = mysqli_fetch_array($check);
  $qtysekarang = $check2['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //stock setelah ditambah
  $stockAdded = $stocksekarang + $qtysekarang;

  $query = mysqli_query($conn, "UPDATE product SET stock='$stockAdded' WHERE idproduct = '$idp'");

  $query2 = mysqli_query($conn, "DELETE FROM detailorder WHERE iddetail='$iddp' and idproduct='$idp' ");


  if ($query && $query2) {
    header('location:view.php?ido=' . $ido);
  } else {
    echo '
    <script> alert("Gagal menghapus detail barang");
    window.location.href="view.php?ido=' . $ido . '"
    </script>
    ';
  }
}


// CUSTOMER

//tambah customer
if (isset($_POST['tambahcustomer'])) {
  $nc = $_POST['namacustomer'];
  $notelp = $_POST['notelp'];
  $alamat = $_POST['alamat'];

  $query = mysqli_query($conn, "INSERT INTO customer (customername, phonenumber, address) VALUES ('$nc','$notelp','$alamat')");

  if ($query) {
    header('location:customer.php');
  } else {
    echo '
    <script> alert("Gagal menambahkan customer baru");
    window.location.href="customer.php"
    </script>
    ';
  }
}



//ubah customer 
if (isset($_POST['ubahcustomer'])) {
  $nc = $_POST['namacustomer'];
  $notelp = $_POST['notelp'];
  $alamat = $_POST['alamat'];
  $idcust = $_POST['idcustomer'];

  $query = mysqli_query($conn, "UPDATE customer SET customername='$nc', phonenumber='$notelp', address='$alamat' WHERE idcustomer = '$idcust'");

  if ($query) {
    header('location: customer.php');
  } else {
    echo '
    <script> alert("Gagal mengedit customer");
    window.location.href="customer.php"
    </script>
    ';
  }
}

//hapus customer
if (isset($_POST['hapuscustomer'])) {
  $idcust = $_POST['idcustomer'];

  $timeDeleted = date('Y-m-d H:i:s');

  $query = mysqli_query($conn, "UPDATE customer SET deleted_at = NOW() WHERE idcustomer='$idcust'");


  if ($query) {
    header('location:customer.php');
  } else {
    echo '
    <script> alert("Gagal menghapus customer");
    window.location.href="customer.php"
    </script>
    ';
  }
}

//restore customer
if (isset($_POST['restorecustomer'])) {
  $idcust = $_POST['idcustomer'];

  $query = mysqli_query($conn, "UPDATE customer SET deleted_at = NULL WHERE idcustomer='$idcust'");


  if ($query) {
    header('location:historycustomer.php');
  } else {
    echo '
    <script> alert("Gagal mengembalikan customer");
    window.location.href="historycustomer.php"
    </script>
    ';
  }
}


// PRODUCT

//tambah barang product
if (isset($_POST['tambahbarang'])) {
  $np = $_POST['namaproduk'];
  $desc = $_POST['deskripsi'];
  $stock = $_POST['stock'];
  $harga = $_POST['harga'];

  $query = mysqli_query($conn, "INSERT INTO product (productname, description, price, stock) VALUES ('$np','$desc','$harga','$stock')");

  if ($query) {
    header('location:product.php');
  } else {
    echo '
    <script> alert("Gagal menambahkan barang baru");
    window.location.href="product.php"
    </script>
    ';
  }
}

//ubah barang 
if (isset($_POST['ubahbarang'])) {
  $np = $_POST['namaproduk'];
  $desc = $_POST['deskripsi'];
  $harga = $_POST['harga'];
  $idp = $_POST['idproduk'];

  $query = mysqli_query($conn, "UPDATE product SET productname='$np', description='$desc', price='$harga' WHERE idproduct = '$idp'");

  if ($query) {
    header('location: product.php');
  } else {
    echo '
    <script> alert("Gagal mengedit barang");
    window.location.href="product.php"
    </script>
    ';
  }
}

//hapus barang
if (isset($_POST['hapusbarang'])) {
  $idp = $_POST['idp'];


  $query = mysqli_query($conn, "UPDATE product SET deleted_at = NOW() WHERE idproduct='$idp'");


  if ($query) {
    header('location:product.php');
  } else {
    echo '
    <script> alert("Gagal menghapus barang");
    window.location.href="product.php"
    </script>
    ';
  }
}
//restore barang
if (isset($_POST['restorebarang'])) {
  $idp = $_POST['idp'];


  $query = mysqli_query($conn, "UPDATE product SET deleted_at = NULL WHERE idproduct='$idp'");


  if ($query) {
    header('location:historyproduct.php');
  } else {
    echo '
    <script> alert("Gagal menghapus barang");
    window.location.href="historyproduct.php"
    </script>
    ';
  }
}


// BARANG MASUK

//tambah barang masuk
if (isset($_POST['tambahbarangmasuk'])) {
  $idp = $_POST['idproduct'];
  $qty = $_POST['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //stock setelah ditambah
  $stockAdded = $stocksekarang + $qty;

  $query = mysqli_query($conn, "INSERT INTO incominggoods (idproduct, qty) VALUES ('$idp','$qty')");

  $query2 = mysqli_query($conn, "UPDATE product SET stock='$stockAdded' WHERE idproduct = '$idp'");

  if ($query && $query2) {
    header('location: incominggoods.php');
  } else {
    echo '
    <script> alert("Gagal menambahkan barang masuk");
    window.location.href="incominggoods.php"
    </script>
    ';
  }
}

//ubah barang masuk
if (isset($_POST['ubahbarangmasuk'])) {
  $idp = $_POST['idp'];
  $idinc = $_POST['idinc'];
  $qty = $_POST['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //ambil qty barang masuk sebelumnya
  $hitung1 = mysqli_query($conn, "SELECT * FROM incominggoods WHERE idinc = '$idinc'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $qtysebelum = $hitung2['qty'];

  //stock setelah diubah
  $stockFinal = $stocksekarang - $qtysebelum + $qty;

  $query = mysqli_query($conn, "UPDATE incominggoods SET qty='$qty' WHERE idinc = '$idinc'");

  $query2 = mysqli_query($conn, "UPDATE product SET stock='$stockFinal' WHERE idproduct = '$idp'");

  if ($query && $query2) {
    header('location: incominggoods.php');
  } else {
    echo '
    <script> alert("Gagal mengubah barang masuk");
    window.location.href="incominggoods.php"
    </script>
    ';
  }
}

//hapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
  $idp = $_POST['idp'];
  $idinc = $_POST['idinc'];

  //ambil qty barang masuk sebelumnya, sebelum dihapus
  $hitung1 = mysqli_query($conn, "SELECT * FROM incominggoods WHERE idinc = '$idinc'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $qtysebelum = $hitung2['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //stock setelah ditambah
  $stockDeleted = $stocksekarang - $qtysebelum;

  $query = mysqli_query($conn, "UPDATE product SET stock='$stockDeleted' WHERE idproduct = '$idp'");

  $query2 = mysqli_query($conn, "UPDATE incominggoods SET deleted_at = NOW() WHERE idinc='$idinc'");


  if ($query && $query2) {
    header('location: incominggoods.php');
  } else {
    echo '
    <script> alert("Gagal menghapus barang masuk");
    window.location.href="incominggoods.php"
    </script>
    ';
  }
}

//restore barang masuk
if (isset($_POST['restorebarangmasuk'])) {
  $idp = $_POST['idp'];
  $idinc = $_POST['idinc'];

  //ambil qty barang masuk sebelumnya
  $hitung1 = mysqli_query($conn, "SELECT * FROM incominggoods WHERE idinc = '$idinc'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $qtysebelum = $hitung2['qty'];

  //hitung stok dlu
  $hitung1 = mysqli_query($conn, "SELECT * FROM product WHERE idproduct = '$idp'");
  $hitung2 = mysqli_fetch_array($hitung1);
  $stocksekarang = $hitung2['stock'];

  //stock setelah ditambah
  $stockRestore = $stocksekarang + $qtysebelum;

  $query = mysqli_query($conn, "UPDATE product SET stock='$stockRestore' WHERE idproduct = '$idp'");

  $query2 = mysqli_query($conn, "UPDATE incominggoods SET deleted_at = NULL WHERE idinc='$idinc'");


  if ($query && $query2) {
    header('location: historyig.php');
  } else {
    echo '
    <script> alert("Gagal menghapus barang masuk");
    window.location.href="historyig.php"
    </script>
    ';
  }
}



// function untuk angka terbilang
function penyebut($nilai)
{
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";


  if ($nilai < 12) {
    $temp = " " . $huruf[$nilai];
  } else if ($nilai < 20) {
    $temp = penyebut($nilai - 10) . " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
  }
  return $temp;
}

function terbilang($nilai)
{
  if ($nilai < 0) {
    $hasil = "minus " . trim(penyebut($nilai));
  } else {
    $hasil = trim(penyebut($nilai));
  }
  return $hasil;
}
