<?php
// Koneksi ke database
$db = new PDO('mysql:host=localhost;dbname=hotel', 'root', '');

// Query untuk mengambil semua paymentid dari tabel ar
$query = $db->query("SELECT payment_id FROM ar");
$payment_ids = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment</title>

  <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Times+New+Roman">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>
  <?php
  include "library.php";
  function formatUang($number, $matauang = true)
  {
    return ($matauang ? "Rp " : "") . number_format($number, 0, ",", ".");
  }
  if (!isset($_REQUEST["payment_id"])) {
  ?>
    <div class="container mx-auto">
      <h1 class="mt-3 text-center">Payment</h1>
      <form action="payment.php" method="post" onsubmit="return validateForm()">
      <div class="mb-3">
    <label for="payment_id" class="form-label">Payment ID</label>
    <select class="form-control" id="payment_id" name="payment_id">
      <option value="">Pilih Salah Satu</option>
        <?php foreach ($payment_ids as $payment_id): ?>
            <option value="<?= $payment_id['payment_id'] ?>"><?= $payment_id['payment_id'] ?></option>
        <?php endforeach; ?>
    </select>
    <div id="error-payment_id" class="alert alert-danger d-none"></div>
</div>
        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" />
          <div id="error-tanggal" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="voucher_id" class="form-label">Voucher ID</label>
          <input type="text" class="form-control" id="voucher_id" name="voucher_id" />
          <div id="error-voucher_id" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="jenis" class="form-label">Jenis</label>
          <select class="form-select" aria-label="jenis" name="jenis" id="jenis">
            <option value="">Pilih Satu</option>
            <option value="Cash">Cash</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Partner">Partner</option>
          </select>
          <div id="error-jenis" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="amount" class="form-label">Amount</label>
          <input type="number" class="form-control" id="amount" name="amount" />
          <div id="error-amount" class="alert alert-danger d-none"></div>
        </div>

        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-light me-2 ">Submit</button>
          <button type="reset" class="btn btn-light">Reset</button>
        </div>
      </form>
    </div>


    <script>
      function gagal() {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Pastikan semua field sudah terisi!",
        });
      }
      $(document).ready(function() {
        $('form').on('submit', function(e) {
          var validations = [{
              field: '#payment_id',
              error: 'error-payment_id',
              condition: $('#payment_id').val() == "" || $('#payment_id').val().length > 6,
              message: 'Payment ID belum diisi atau lebih dari 6 karakter'
            },
            {
              field: '#tanggal',
              error: 'error-tanggal',
              condition: $('#tanggal').val() == "",
              message: 'Tanggal belum diisi'
            },
            {
              field: '#voucher_id',
              error: 'error-voucher_id',
              condition: $('#voucher_id').val() == "" || $('#voucher_id').val().length > 20,
              message: 'Voucher ID belum diisi atau lebih dari 20 karakter'
            },
            {
              field: '#jenis',
              error: 'error-jenis',
              condition: $('#jenis').val() == "",
              message: 'Tolong pilih satu jenis'
            },
            {
              field: '#amount',
              error: 'error-amount',
              condition: $('#amount').val() == "" || isNaN($('#amount').val()),
              message: 'Amount belum diisi atau bukan angka'
            }
          ];
          $('.alert').addClass('d-none');
          $('.alert').text('');

          validations.forEach(function(validation) {
            if (validation.condition) {
              $('#' + validation.error).removeClass('d-none');
              $('#' + validation.error).text(validation.message);
              e.preventDefault();
            }
          });
          if (!e.isDefaultPrevented()) {
            succes(e);
          } else {
            gagal();
          }
        });
      });
    </script>

  <?php
  } else {
    echo '<div class="container col-md-6 mx-auto">';
    echo "<h1 class=\"mt-3 text-center\">Result</h1>";
    echo '</div>';
    echo '<br>';
    echo '<br>';


    $data = array();
    $data["payment_id"] = $_REQUEST["payment_id"];
    $data["tanggal"] = $_REQUEST["tanggal"];
    $data["voucher_id"] = $_REQUEST["voucher_id"];
    $data["jenis"] = $_REQUEST["jenis"];
    $data["amount"] = $_REQUEST["amount"];

    $sqlI = <<<EOD
INSERT INTO payment(payment_id, tanggal, voucher_id, jenis, amount)
values (:payment_id, :tanggal, :voucher_id, :jenis, :amount)
ON DUPLICATE KEY UPDATE
tanggal=:tanggal, voucher_id=:voucher_id, jenis=:jenis, amount=:amount;
EOD;

    $con = openConnection();
    createRow($con, $sqlI, $data);

    $sqlS = <<<EOD
SELECT payment_id, tanggal, voucher_id, jenis, amount FROM payment Where payment_id=:payment_id;
EOD;

    $hasil = queryArrayValue1($con, $sqlS, array("payment_id" => $_REQUEST["payment_id"]));

    if ($hasil) {
      echo '<div class="container col-md-6 mx-auto">';
      echo '<table class="table">';
      echo '<thead>';
      echo '<tr>';
      echo '<th scope="col">Key</th>';
      echo '<th scope="col">Value</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
      foreach ($hasil as $key => $value) {
        echo '<tr>';
        echo '<td>' . ucfirst(str_replace("_", " ", $key)) . '</td>';
        echo '<td>' . ($key === 'amount' ? formatUang($value) : $value) . '</td>';

        echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
      echo '</div>';
      echo '<script>$(document).ready(function() {
    succes();
  });

  function succes() {
    Swal.fire({
      icon: "success",
      title: "Data anda telah tersimpan!",
      showConfirmButton: false,
      timer: 1500
    });
  }</script>';
    } else {
      echo "Tidak ditemukan";
    }
  }
  ?>

  <script type="text/javascript" src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
</body>

</html>