<?php
require 'function.php';
if (!isset($_SESSION)) session_start();
if (isset($_SESSION['login'])) {
} else {
  echo '
    <script> alert("Session anda sudah habis. Silahkan login terlebih dahulu");
    window.location.href="login.php"
    </script>
    ';
}
